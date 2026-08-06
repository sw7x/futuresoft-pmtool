<?php
namespace Modules\Project\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Modules\Project\Models\Project;
use Modules\Project\Models\ProjectPhase;
use Faker\Factory as FakerFactory;

class ProjectPhaseSeeder extends Seeder
{
    protected static array $phaseNames = [
        'Discovery & Requirements',
        'Design',
        'Development',
        'Testing & QA',
        'UAT',
        'Deployment',
        'Post-Launch Support',
    ];

    /**
     * Run the database seeder.
     *
     * Generates 3-6 rule-compliant phases for every existing project that
     * doesn't already have phases. Safe to re-run — projects that already
     * have phases are skipped.
     */
    public function run(): void
    {
        $query = Project::query()->doesntHave('phases');
        $total = $query->count();

        if ($total === 0) {
            $this->command->info('No projects without phases found. Nothing to seed.');
            return;
        }

        $this->command->info("Generating phases for {$total} project(s)...");

        $query->chunkById(50, function ($projects) {
            foreach ($projects as $project) {
                $this->createPhasesForProject($project);
            }
        });

        $this->command->info('Done.');
    }

    /**
     * Build and persist a full, rule-compliant chain of phases for a single project.
     */
    protected function createPhasesForProject(Project $project): void
    {
        $faker = FakerFactory::create();

        $now = Carbon::now();
        $count = $faker->numberBetween(3, 6);

        // ---- Bounds coming from the project itself ----
        $projectStart = $project->start_at ? Carbon::parse($project->start_at) : Carbon::parse($project->created_at);
        $projectPlannedEnd = $project->planned_to_delivery_at
            ? Carbon::parse($project->planned_to_delivery_at)
            : $projectStart->copy()->addDays(30 * $count); // fallback if not set

        $actualDeliveredInPast = $project->actual_delivery_at
            && Carbon::parse($project->actual_delivery_at)->lessThan($now);

        // ---- 1. Progress chain ----
        // "if project.actual_delivery_at < now() then make all phases complete" overrides everything else.
        if ($actualDeliveredInPast) {
            $progressChain = array_fill(0, $count, 'completed');
        } else {
            $progressChain = $this->buildProgressChain($project->progress, $count, $faker);
        }

        // ---- 2. Scheduled windows (ALL phases, regardless of progress) ----
        // first phase scheduled_start >= project.start_at
        // last phase scheduled_end <= project.planned_to_delivery_at
        $scheduledWindows = $this->generateSequentialWindows($projectStart, $projectPlannedEnd, $count);

        // ---- 3. Actual windows (only for the leading "active" phases, i.e. progress != not_started) ----
        // Because of the transition rules, phases with progress != not_started always form a
        // contiguous PREFIX of the chain (completed... then one active phase, then not_started tail).
        $activeCount = 0;
        foreach ($progressChain as $p) {
            if ($p === 'not_started') {
                break;
            }
            $activeCount++; // active phase count (phases progress != not_started )
        }

        $actualWindows = [];
        $actualFloor = null;
        $actualCeiling = null;

        if ($activeCount > 0) {
            $actualFloor = $project->start_at ? Carbon::parse($project->start_at) : null;

            // Ceiling for actual dates:
            // - if project has actually been delivered (past or future target), use that date
            // - if not delivered yet and no target set, we can't have actual dates beyond "now"
            $actualCeiling = $project->actual_delivery_at
                ? Carbon::parse($project->actual_delivery_at)
                : $now->copy();

            // actual time periods of the phases are filled if project is started(project.start_at) , if not not filled
            if ($actualFloor !== null) {
                $actualWindows = $this->generateSequentialWindows($actualFloor, $actualCeiling, $activeCount);
            }
        }

        // ---- 4. Persist phases ----
        //tracks the actual_end of the last phase that had one
        $prevActualEnd = null;

        for ($i = 0; $i < $count; $i++) {
            $phaseProgress = $progressChain[$i];
            [$scheduledStart, $scheduledEnd] = $scheduledWindows[$i];

            // phases that not_started has no actual_start or actual_end value because nothing has happened yet.
            $actualStart = null;
            $actualEnd = null;

            // if block only runs for active phases (phases progress != not_started )
            if ($i < $activeCount && ! empty($actualWindows)) {
                [$rawActualStart, $rawActualEnd] = $actualWindows[$i];

                // Apply the scheduled_start vs actual_start relationship:
                // 75% actual after scheduled, 20% actual before scheduled, 5% equal.
                $actualStart = $this->applyScheduleVsActualBias(
                    $scheduledStart,
                    $rawActualStart,
                    $faker
                );

                // FIX (regression from a previous version — actual_end could exceed
                // project.actual_delivery_at, and even actual_start < project.start_at,
                // because the bias step reasons about scheduled_start, which lives on a
                // completely different timeline [project.start_at, project.planned_to_delivery_at]
                // than actual_start's own timeline [project.start_at, project.actual_delivery_at].
                // A "late start" bias could push actual_start arbitrarily far forward with no
                // awareness of how close that is to the actual_delivery_at ceiling.
                //
                // [rawActualStart, rawActualEnd) is already PROVEN safe: generateSequentialWindows()
                // guarantees it fits inside [actualFloor, actualCeiling] and never collides with the
                // next phase's window. So instead of trying to re-derive safety checks after the bias
                // has already moved us who-knows-where, we simply fall back into that pre-validated
                // window whenever the bias pushes actual_start outside of it. This guarantees ceiling
                // safety by construction, with no special-casing required.
                if ($actualStart->greaterThanOrEqualTo($rawActualEnd)) {
                    $actualStart = $rawActualStart->copy();
                }

                // Keep monotonic order intact: must be after the previous phase's actual_end,
                // and (if it needs an end date) before that end date.
                if ($prevActualEnd !== null && $actualStart->lessThanOrEqualTo($prevActualEnd)) {
                    $actualStart = $prevActualEnd->copy()->addMinute();
                }

                // Never let any phase's actual_start fall before project.start_at.
                if ($actualFloor !== null && $actualStart->lessThan($actualFloor)) {
                    $actualStart = $actualFloor->copy();
                }

                // $needsEnd checks, has this phase actually finished?
                $needsEnd = $phaseProgress === 'completed';

                if ($needsEnd) {
                    // if this phase completed then it has a finish date.
                    // rawActualEnd is already ceiling-safe; only fall back to a fresh minimal
                    // end if actualStart got pushed at/after it by the clamps above.
                    $candidateEnd = $rawActualEnd->greaterThan($actualStart)
                        ? $rawActualEnd
                        : $actualStart->copy()->addMinute();

                    // Belt-and-suspenders: never let the final actual_end exceed the project's
                    // actual_delivery_at boundary, however it was derived.
                    if ($actualCeiling !== null && $candidateEnd->greaterThan($actualCeiling)) {
                        $candidateEnd = $actualCeiling->copy()->subMinute();
                        if ($candidateEnd->lessThanOrEqualTo($actualStart)) {
                            $candidateEnd = $actualStart->copy()->addMinute();
                        }
                    }

                    $actualEnd = $candidateEnd;
                    $prevActualEnd = $actualEnd;
                } else {
                    // in_progress / blocked / cancelled active phase: still open, no end date yet.
                    // if this phase not completed then it has a no finish date ($actualEnd = null)

                    /*
                    Since this phase has no actual_end (it's still open), there's nothing to hand off as the "previous end" boundary.
                    So the code uses $actualStart itself as the stand-in floor for the next phase.
                    */
                    $prevActualEnd = $actualStart;

                    /*
                    But remember — this branch only ever runs for the single active phase in the chain (since not_started phases skip the
                    whole outer if block entirely, per the earlier explanation). And by construction, that active phase is always the last
                    phase with actual dates — everything after it is not_started. So in practice, this else branch's $prevActualEnd
                    assignment doesn't actually get used again; it's mostly just keeping the variable in a sane state for
                    consistency/future-proofing, not because a later iteration depends on it.
                    */
                }
            }

            $projectPhaseRec = new ProjectPhase([
                'project_id' => $project->id,
                'name' => self::$phaseNames[$i] ?? ('Phase ' . ($i + 1)),
                'description' => $faker->optional(0.7)->sentence(12),
                'order' => $i + 1,
                'progress' => $phaseProgress,
                'scheduled_start' => $scheduledStart,
                'scheduled_end' => $scheduledEnd,
                'actual_start' => $actualStart,
                'actual_end' => $actualEnd,
            ]);
            $projectCreated = Carbon::parse($project->created_at);
            $phaseCreated = $projectCreated->copy()->addDays(1)->addHours(1)->addMinutes(30 * $count);
            
            $projectPhaseRec->forceFill([
                'created_at' => $phaseCreated,
                'updated_at' => $phaseCreated,
            ])->save();
        }
    }

    /**
     * Build an array of `$count` progress values honoring the transition rules:
     *   - completed  -> next phase can be anything
     *   - anything else -> next phase MUST be not_started
     * and reflecting the project's own progress:
     *   - not_started -> every phase not_started
     *   - completed   -> every phase completed
     *   - otherwise   -> N completed phases, then ONE active phase mirroring the
     *                     project's status, then the rest not_started
     */
    protected function buildProgressChain(string $projectProgress, int $count, $faker): array
    {
        if ($projectProgress === 'not_started') {
            return array_fill(0, $count, 'not_started');
        }

        if ($projectProgress === 'completed') {
            return array_fill(0, $count, 'completed');
        }

        $completedCount = $faker->numberBetween(0, $count - 1);

        $chain = array_fill(0, $completedCount, 'completed');
        $chain[] = $projectProgress; // in_progress / blocked / cancelled
        while (count($chain) < $count) {
            $chain[] = 'not_started';
        }

        return $chain;
    }

    /**
     * Generate `$n` strictly increasing, non-overlapping [start, end] date windows
     * bounded within [$floor, $ceiling]:  start(1) < end(1) < start(2) < end(2) < ... < start(n) < end(n)
     * with start(1) >= $floor and end(n) <= $ceiling.
     *
     * FIX (v2 — replaces the earlier "generate loosely, then clamp backward" approach,
     * which could invert/collide windows when the floor->ceiling range was tight relative
     * to $n, or when a later cascading fix touched an already-finalized neighbor).
     *
     * This version instead PARTITIONS [$floor, $ceiling] into $n equal, non-overlapping
     * time slots (minute-precision) up front, then picks a randomized [start, end] pair
     * strictly INSIDE each slot. Because the slots themselves never overlap and the last
     * slot always ends exactly at $ceiling, every window is correct by construction —
     * no post-hoc clamping/cascading is needed, which is what caused the earlier bugs.
     *
     * @return array<int, array{0: Carbon, 1: Carbon}>
     */
    protected function generateSequentialWindows(Carbon $floor, Carbon $ceiling, int $n): array
    {
        $faker = FakerFactory::create();

        // Guard against an inverted/degenerate range (ceiling not after floor).
        if ($ceiling->lessThanOrEqualTo($floor)) {
            $ceiling = $floor->copy()->addMinutes(max($n * 4, 10));
        }

        $totalMinutes = max($floor->diffInMinutes($ceiling), $n);
        $slotWidth = max(intdiv($totalMinutes, $n), 1);

        $windows = [];
        $slotStart = $floor->copy();

        for ($i = 0; $i < $n; $i++) {
            $isLast = $i === $n - 1;

            // Every slot boundary is derived purely from $floor + a multiple of $slotWidth,
            // except the very last one, which is pinned exactly to $ceiling so we never
            // drift past the real project boundary no matter how the division rounded.
            $slotEnd = $isLast ? $ceiling->copy() : $floor->copy()->addMinutes(($i + 1) * $slotWidth);
            if ($slotEnd->greaterThan($ceiling)) {
                $slotEnd = $ceiling->copy();
            }
            if ($slotEnd->lessThanOrEqualTo($slotStart)) {
                $slotEnd = $slotStart->copy()->addMinute();
            }

            $availableMinutes = max($slotStart->diffInMinutes($slotEnd), 2);

            // Start somewhere in the first third of the slot, end somewhere after start but
            // strictly before the slot boundary — leaving room for the next phase to start
            // strictly after this slot ends.
            $startJitter = $faker->numberBetween(0, max(intdiv($availableMinutes, 3), 0));
            $start = $slotStart->copy()->addMinutes($startJitter);

            $maxDuration = max($availableMinutes - $startJitter - 1, 1);
            $duration = $faker->numberBetween(1, $maxDuration);
            $end = $start->copy()->addMinutes($duration);

            // Defensive: guarantee end stays strictly inside the slot even at extreme rounding.
            if ($end->greaterThanOrEqualTo($slotEnd)) {
                $end = $slotEnd->copy()->subMinute();
            }
            if ($end->lessThanOrEqualTo($start)) {
                $start = $end->copy()->subMinute();
            }

            $windows[] = [$start, $end];
            $slotStart = $slotEnd->copy();
        }

        return $windows;
    }

    /**
     * Nudge a raw actual_start candidate so it reflects the desired
     * scheduled_start vs actual_start relationship:
     *   75% -> scheduled_start < actual_start (started late)
     *   20% -> scheduled_start > actual_start (started early)
     *    5% -> scheduled_start = actual_start (started on time)
     *
     * Note: this method deliberately does NOT know about project.start_at/actual_delivery_at —
     * it only reasons about the relationship between scheduled_start and actual_start. The
     * caller (createPhasesForProject) is responsible for confining the result back into the
     * phase's own pre-validated [rawActualStart, rawActualEnd) window, since only it has that
     * context (see the comment at the call site for why this matters).
     */
    protected function applyScheduleVsActualBias(Carbon $scheduledStart, Carbon $rawActualStart, $faker): Carbon
    {
        $faker = FakerFactory::create();
        $roll = $faker->numberBetween(1, 100);

        if ($roll <= 75) {
            // Want: scheduled_start < actual_start (started late)
            if ($scheduledStart->lessThan($rawActualStart)) {
                return $rawActualStart;
            }
            return $scheduledStart->copy()->addDays($faker->numberBetween(1, 7));
        }

        if ($roll <= 95) {
            // Want: scheduled_start > actual_start (started early)
            if ($scheduledStart->greaterThan($rawActualStart)) {
                return $rawActualStart;
            }
            return $scheduledStart->copy()->subDays($faker->numberBetween(1, 5));
        }

        // Started exactly on time.
        return $scheduledStart->copy();
    }
}


//==============  projectphase created_at

/*
for single project theere is multiple phases phase count can be 3 to 6

phase transition is like this
    in a project if any phase progress  = not_started  next phase progress  = not_started 
    in a project if any phase progress  = in_progress  next phase progress  = not_started
    in a project if any phase progress  = completed  next phase progress  = not_started or in_progress or completed or blocked or cancelled 
    in a project if any phase progress  = blocked  next phase progress  = not_started
    in a project if any phase progress  = cancelled  next phase progress  = not_started

if project.actual_delivery_at  < now()    then make all phase complete


in phases of a single project (in here n is phase number)

    scheduled_start of phase n < scheduled_end  of phase n  <  scheduled_start  of phase n+1 <  scheduled_end of phase n+1
    actual_start of phase n < actual_end  of phase n  <  actual_start of phase n+1 <  actual_end of phase n+1

    scheduled_start of phase n < actual_start of phase n (75% change)
    scheduled_start of phase n > actual_start of phase n (20% change)
    scheduled_start of phase n = actual_start of phase n (5% change)


how project phases time relate with project.start_at & project.planned_to_delivery_at
    first phase scheduled_start >= project.start_at
    last phase scheduled_end =< project.planned_to_delivery_at

    first phase actual_start >= project.start_at
    last phase actual_end =< project.actual_delivery_at
*/




/**
 * ===========================================================================
 * PROJECT PHASE SEEDING RULES
 * ===========================================================================
 *
 * 1. PHASE COUNT
 * ---------------------------------------------------------------------------
 *   - Each project has between 3 and 6 phases.
 *
 *
 * 2. PHASE PROGRESS TRANSITION RULES
 * ---------------------------------------------------------------------------
 *   For any phase N in a project, its progress value restricts what the
 *   NEXT phase (N+1) is allowed to be:
 *
 *     - not_started -> next phase MUST be not_started
 *     - in_progress -> next phase MUST be not_started
 *     - blocked     -> next phase MUST be not_started
 *     - cancelled   -> next phase MUST be not_started
 *     - completed   -> next phase CAN be any of:
 *                        not_started, in_progress, completed, blocked, cancelled
 *
 *   In short: only a "completed" phase allows the chain to continue with
 *   anything other than not_started. Every other status forces everything
 *   after it to be not_started.
 *
 *
 * 3. PROJECT-LEVEL OVERRIDE
 * ---------------------------------------------------------------------------
 *   - If project.actual_delivery_at < now(), ALL phases are forced to
 *     'completed', regardless of the transition rules above.
 *
 *
 * 4. SCHEDULED DATE ORDERING (applies to ALL phases)
 * ---------------------------------------------------------------------------
 *   For every phase N and its successor N+1:
 *
 *     scheduled_start(N) < scheduled_end(N)
 *                        < scheduled_start(N+1) < scheduled_end(N+1)
 *
 *   i.e. scheduled windows are strictly increasing and never overlap.
 *
 *
 * 5. ACTUAL DATE ORDERING (applies only to phases with actual dates)
 * ---------------------------------------------------------------------------
 *   For every phase N and its successor N+1:
 *
 *     actual_start(N) < actual_end(N)
 *                     < actual_start(N+1) < actual_end(N+1)
 *
 *   i.e. actual windows are strictly increasing and never overlap.
 *
 *
 * 6. SCHEDULED vs ACTUAL START RELATIONSHIP (per phase)
 * ---------------------------------------------------------------------------
 *   For each phase N, compare scheduled_start(N) to actual_start(N):
 *
 *     - 75% chance: scheduled_start(N) < actual_start(N)   (started late)
 *     - 20% chance: scheduled_start(N) > actual_start(N)   (started early)
 *     -  5% chance: scheduled_start(N) = actual_start(N)   (started on time)
 *
 *
 * 7. PROJECT-LEVEL DATE BOUNDARIES
 * ---------------------------------------------------------------------------
 *   Scheduled dates:
 *     - First phase: scheduled_start >= project.start_at
 *     - Last phase:  scheduled_end   <= project.planned_to_delivery_at
 *
 *   Actual dates:
 *     - First phase: actual_start >= project.start_at
 *     - Last phase:  actual_end   <= project.actual_delivery_at
 *
 * ===========================================================================
 */


