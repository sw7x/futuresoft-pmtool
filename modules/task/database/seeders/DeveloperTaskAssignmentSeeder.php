<?php
namespace Modules\Task\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Project\Models\Project;
use Modules\Project\Models\DeveloperProjectEnrollment;
use Modules\Task\Models\Task;
use Modules\Task\Models\DeveloperTaskAssignment;

class DeveloperTaskAssignmentSeeder extends Seeder
{
    // ==================== TUNABLE CONFIG ====================
    // Change these numbers to reshape the generated data.

    // Chance that a task / task-group gets any assignment at all.
    // (15% of tasks stay completely unassigned)
    private const ASSIGN_PROBABILITY = 0.85;

    // For a task that HAS children: chance the whole family is assigned as
    // one unit to a single developer (MODE A) vs letting children be
    // assigned individually while the parent stays unassigned (MODE B).
    private const PARENT_GROUP_ASSIGN_PROBABILITY = 0.5;

    // How many developers get attached to the SAME task (only used for
    // standalone tasks / individually-assigned children - MODE A always
    // uses exactly one developer for the whole family).
    private const DEVELOPER_COUNT_WEIGHTS = [
        1 => 85,
        2 => 10,
        3 => 5,
    ];

    // Distribution of the "progress" value for a freshly created assignment.
    private const PROGRESS_WEIGHTS = [
        'not_started' => 15,
        'in_progress' => 25,
        'blocked'     => 10,
        'completed'   => 40,
        'cancelled'   => 10,
    ];

    // spend_time bounds in minutes, strictly between these two numbers:
    // 5 < spend_time < (5 days * 8h * 60) + 1000 = 3400
    private const MIN_SPEND_TIME = 6;
    private const MAX_SPEND_TIME = (5 * 8 * 60) + 1000 - 1; // 3399

    // finished_date_time bounds relative to assigned_date_time, strictly between:
    // assigned + 5 minutes < finished < assigned + 2 weeks
    private const MIN_FINISH_AFTER_MINUTES = 6;
    private const MAX_FINISH_AFTER_MINUTES = (60 * 24 * 14) - 1; // 2 weeks minus 1 minute

    private const MIN_CANCELLED_AFTER_MINUTES = 6;
    private const MAX_CANCELLED_AFTER_MINUTES = (60 * 24 * 14) - 1; // 2 weeks minus 1 minute
	
	private const MIN_BLOCKED_AFTER_MINUTES = 6;
    private const MAX_BLOCKED_AFTER_MINUTES = (60 * 24 * 14) - 1; // 2 weeks minus 1 minute
    // ==================== ENTRY POINT ====================

    public function run(): void
    {
        Project::with(['tasks', 'developerEnrollments'])->get()->each(function (Project $project) {
            $this->seedProject($project);
        });
    }

    private function seedProject(Project $project): void
    {
        $enrollments = $project->developerEnrollments;

        // No developers enrolled on this project -> nothing can be assigned.
        if ($enrollments->isEmpty()) {
            return;
        }

        // Only look at root tasks here, we walk down to children ourselves
        // so that a parent/child pair is always decided together.
        $rootTasks = $project->tasks()->whereNull('parent_task_id')->get();

        foreach ($rootTasks as $rootTask) {
            $this->seedTaskGroup($rootTask, $project, $enrollments);
        }
    }

    // ==================== GROUP LEVEL LOGIC ====================

    private function seedTaskGroup(Task $rootTask, Project $project, Collection $enrollments): void
    {
        $children = $rootTask->childTasks()->get();

        // No children -> just a normal standalone task.
        if ($children->isEmpty()) {
            $this->maybeAssignStandaloneTask($rootTask, $project, $enrollments);
            return;
        }

        // Decide if this family gets any assignment at all.
        if (!$this->rollProbability(self::ASSIGN_PROBABILITY)) {
            return; // whole family stays unassigned (no rows at all)
        }

        if ($this->rollProbability(self::PARENT_GROUP_ASSIGN_PROBABILITY)) {
            // MODE A: parent task assigned -> children forced onto same developer
            $this->assignParentGroup($rootTask, $children, $project, $enrollments);
        } else {
            // MODE B: parent stays unassigned -> children assigned individually,
            // but the parent still gets its own row with aggregated values.
            $this->assignChildrenIndividually($rootTask, $children, $project, $enrollments);
        }
    }

    /**
     * MODE A: parent + all children share one developer and one assigned_date_time.
     * The parent's own row is calculated from whatever ends up on the children.
     */
    private function assignParentGroup(Task $parent, Collection $children, Project $project, Collection $enrollments): void
    {
        $enrollment = $enrollments->random();
        $assignedAt = $this->randomAssignedDateTime($project, $enrollment, $parent);

        $childRows = [];

        foreach ($children as $child) {
            $row = $this->buildAssignmentRow($child, $enrollment, $assignedAt);
            $this->insertAssignmentRow($row);
            $childRows[] = $row;
        }

        // Every child here is exactly ONE row, so we can aggregate the raw
        // rows directly (no need to reduce a child's own multiple rows first).
        $this->insertAssignmentRow(
            $this->buildParentRowFromChildren($parent, $enrollment, $assignedAt, $childRows)
        );
    }

    /**
     * MODE B: parent stays unassigned (no developer, no assigned_date_time),
     * each child is assigned independently (or not) via maybeAssignStandaloneTask.
     * The parent still gets its own row, aggregated from whatever the children
     * ended up with.
     */
    private function assignChildrenIndividually(Task $parent, Collection $children, Project $project, Collection $enrollments): void
    {
        // One "effective state" per child - a child may have 0 rows (not
        // assigned), 1 row, or up to 3 rows (multiple developers), so we
        // first reduce each child down to a single progress/finished/spend
        // summary before aggregating across children for the parent.
        $childStates = [];

        foreach ($children as $child) {
            $childRows = $this->maybeAssignStandaloneTask($child, $project, $enrollments);
            $childStates[] = $this->reduceChildRowsToState($childRows);
        }

        $this->insertAssignmentRow(
            $this->buildParentRowFromChildStates($parent, $childStates)
        );
    }

    /**
     * Reduce a child task's own rows (0 to 3, since a leaf task can be
     * assigned to multiple developers) down to a single summary:
     *   - progress: 'not_started' if the child has no rows at all (not assigned), 
     *     the shared progress if every row of the child agrees,otherwise 'mixed'.
     *   - finished_date_time: only set if the child HAS rows and every one of them is finished, otherwise null.
     *   - spend_time: sum of whatever spend_time values the child's rows have.
     */
    private function reduceChildRowsToState(array $childRows): array
    {
        if (empty($childRows)) {
            return ['progress' => 'not_started', 'finished_date_time' => null, 'spend_time' => null];
        }

        $progresses = array_unique(array_column($childRows, 'progress'));
        $progress = count($progresses) === 1 ? $progresses[0] : 'mixed';

        $finishedTimes = array_column($childRows, 'finished_date_time');
        $allFinished = !in_array(null, $finishedTimes, true);
        $finishedAt = $allFinished ? collect($finishedTimes)->max() : null;

        $spendTimes = array_filter(array_column($childRows, 'spend_time'), fn ($v) => $v !== null);
        $spendTime = count($spendTimes) > 0 ? array_sum($spendTimes) : null;

        return ['progress' => $progress, 'finished_date_time' => $finishedAt, 'spend_time' => $spendTime];
    }

    /**
     * Builds the parent's assignment row purely from the child rows we just created:
     *   - progress: same value if every child matches, otherwise 'mixed'
     *   - finished_date_time: latest child finish time, but only if EVERY child finished
     *   - spend_time: sum of all child spend_time values
     */
    private function buildParentRowFromChildren(Task $parent, DeveloperProjectEnrollment $enrollment, Carbon $assignedAt, array $childRows): array
    {
        $progresses = array_unique(array_column($childRows, 'progress'));
        $parentProgress = count($progresses) === 1 ? $progresses[0] : 'mixed';

        $finishedTimes = array_column($childRows, 'finished_date_time');
        $allChildrenFinished = !in_array(null, $finishedTimes, true);
        $parentFinishedAt = $allChildrenFinished && count($finishedTimes) > 0
            ? collect($finishedTimes)->max()
            : null;

        $spendTimes = array_filter(array_column($childRows, 'spend_time'), fn ($v) => $v !== null);
        $parentSpendTime = count($spendTimes) > 0 ? array_sum($spendTimes) : null;

        return [
            'task_id' => $parent->id,
            'developer_project_enrollment_id' => $enrollment->id,
            'is_notify' => true,
            'assigned_date_time' => $assignedAt,
            'finished_date_time' => $parentFinishedAt,
			'stopped_date_time' => null,
            'spend_time' => $parentSpendTime,
            'progress' => $parentProgress,
            'created_at' => $assignedAt, // created_at mirrors assigned_date_time
        ];
    }

    /**
     * Builds the parent's row for MODE B (parent itself is NOT assigned),
     * aggregating over the already-reduced per-child states.
     */
    private function buildParentRowFromChildStates(Task $parent, array $childStates): array
    {
        $progresses = array_unique(array_column($childStates, 'progress'));
        $parentProgress = count($progresses) === 1 ? $progresses[0] : 'mixed';

        $finishedTimes = array_column($childStates, 'finished_date_time');
        $allChildrenFinished = !empty($childStates) && !in_array(null, $finishedTimes, true);
        $parentFinishedAt = $allChildrenFinished ? collect($finishedTimes)->max() : null;

        $spendTimes = array_filter(array_column($childStates, 'spend_time'), fn ($v) => $v !== null);
        $parentSpendTime = count($spendTimes) > 0 ? array_sum($spendTimes) : null;

        return [
            'task_id' => $parent->id,
            'developer_project_enrollment_id' => null,
            'is_notify' => false,
            'assigned_date_time' => null,
            'finished_date_time' => $parentFinishedAt,
			'stopped_date_time' => null,
            'spend_time' => $parentSpendTime,
            'progress' => $parentProgress,
            'created_at' => now(), // parent was never assigned, so there's no assigned_date_time to mirror
        ];
    }

    // ==================== SINGLE TASK LOGIC ====================

    /**
     * Used for: standalone tasks (no children) and children assigned individually (MODE B).
     * May attach 1, 2 or 3 developers to the SAME task_id (weighted).
     *
     * Returns the list of row arrays that were actually inserted (empty
     * array if the task ended up not assigned), so callers can aggregate
     * this information further up (e.g. MODE B's parent row).
     */
    private function maybeAssignStandaloneTask(Task $task, Project $project, Collection $enrollments): array
    {
        if (!$this->rollProbability(self::ASSIGN_PROBABILITY)) {
            return []; // this task stays unassigned
        }

        $developerCount = min(
            $this->weightedRandomKey(self::DEVELOPER_COUNT_WEIGHTS),
            $enrollments->count()
        );

        // assign one task to multiple developers = one task assignment connect with multiple project enrollments
        $chosenEnrollments = $enrollments->random($developerCount);
        if (!$chosenEnrollments instanceof Collection) {
            $chosenEnrollments = collect([$chosenEnrollments]);
        }

        $insertedRows = [];

        foreach ($chosenEnrollments as $enrollment) {
            $assignedAt = $this->randomAssignedDateTime($project, $enrollment, $task);
            $row = $this->buildAssignmentRow($task, $enrollment, $assignedAt);
            $this->insertAssignmentRow($row);
            $insertedRows[] = $row;
        }

        return $insertedRows;
    }

    /**
     * Builds one developer_task_assignment row following the 3 named scenarios:
     *   - not finished (not_started / in_progress / blocked): no finish date, no spend time
     *   - completed: finish date set, spend time set
     *   - cancelled: no finish date, but spend time set (time was spent before cancelling)
     */
    private function buildAssignmentRow(Task $task, DeveloperProjectEnrollment $enrollment, Carbon $assignedAt): array
    {
        $progress = $this->weightedRandomKey(self::PROGRESS_WEIGHTS);

        $finishedAt = null;
        $spendTime = null;
		$stoppedAt = null;

        if ($progress === 'completed') {
            $finishedAt = $this->randomFinishedDateTime($assignedAt);
            $spendTime = random_int(self::MIN_SPEND_TIME, self::MAX_SPEND_TIME);
        } elseif ($progress === 'cancelled') {
            $spendTime = random_int(self::MIN_SPEND_TIME, self::MAX_SPEND_TIME);
			$stoppedAt = $this->randomCancelledDateTime($assignedAt);
            // $finishedAt = null;
        } else {
            // when task assigned and not finished

            // $spendTime = null;
            // $finishedAt = null;
            // $progress = not_started / in_progress / blocked
			// $stoppedAt = null;
        }

        return [
            'task_id' => $task->id,
            'developer_project_enrollment_id' => $enrollment->id,
            'is_notify' => true,
            'assigned_date_time' => $assignedAt,
            'finished_date_time' => $finishedAt,
			'stopped_date_time' => $stoppedAt,
            'spend_time' => $spendTime,
            'progress' => $progress,
            'created_at' => $assignedAt, // created_at mirrors assigned_date_time
        ];
    }

    // ==================== PERSISTENCE HELPER ====================

    /**
     * Inserts a developer_task_assignments row.
     *
     * IMPORTANT: 'created_at' is deliberately NOT in the model's $fillable
     * list, so simply doing DeveloperTaskAssignment::create($attributes)
     * would have mass-assignment protection silently drop the created_at we
     * computed, and Eloquent would stamp the real current time instead -
     * breaking the "created_at = assigned_date_time" requirement. To avoid
     * that, we build the model, set created_at/updated_at directly on the
     * instance (bypassing $fillable), then save.
     */
    private function insertAssignmentRow(array $attributes): DeveloperTaskAssignment
    {
        $createdAt = $attributes['created_at'] ?? now();
        unset($attributes['created_at']);

        $row = new DeveloperTaskAssignment($attributes);
        $row->timestamps = false; // stop Eloquent from auto-stamping now()
        $row->created_at = $createdAt;
        $row->updated_at = $createdAt;
        $row->save();

        return $row;
    }

    // ==================== DATE HELPERS ====================

    /**
     * assigned_date_time must come strictly after: project.start_at, the
     * developer's enrollment.assigned_date_time, AND the task's own created_at.
     */
    private function randomAssignedDateTime(Project $project, DeveloperProjectEnrollment $enrollment, Task $task): Carbon
    {
        $lowerBound = collect([
            $project->start_at,
            $enrollment->assigned_date_time,
            $task->created_at,
        ])->filter()->max();

        $lowerBound = $lowerBound ? Carbon::parse($lowerBound) : now()->subMonths(3);

        // Push strictly past the lower bound (avoid landing exactly on it).
        $lowerBound = $lowerBound->copy()->addMinute();

        $upperBound = now();
        if ($lowerBound->greaterThanOrEqualTo($upperBound)) {
            $upperBound = $lowerBound->copy()->addDay();
        }

        return $this->randomDateTimeBetween($lowerBound, $upperBound);
    }

    private function randomFinishedDateTime(Carbon $assignedAt): Carbon
    {
        return $assignedAt->copy()->addMinutes(
            random_int(self::MIN_FINISH_AFTER_MINUTES, self::MAX_FINISH_AFTER_MINUTES)
        );
    }
	private function randomCancelledDateTime(Carbon $assignedAt): Carbon
    {
        return $assignedAt->copy()->addMinutes(
            random_int(self::MIN_CANCELLED_AFTER_MINUTES, self::MAX_CANCELLED_AFTER_MINUTES)
        );
    }
    private function randomDateTimeBetween(Carbon $start, Carbon $end): Carbon
    {
        if ($start->greaterThanOrEqualTo($end)) {
            return $start->copy();
        }

        return Carbon::createFromTimestamp(random_int($start->timestamp, $end->timestamp));
    }

    // ==================== RANDOM HELPERS ====================

    private function rollProbability(float $probability): bool
    {
        return (mt_rand() / mt_getrandmax()) < $probability;
    }

    /**
     * Picks a key from a ['key' => weight] array, weighted by the given weights.
     * Works for both string keys (progress) and int keys (developer count).
     */
    private function weightedRandomKey(array $weights)
    {
        $total = array_sum($weights);
        $rand = random_int(1, $total);

        foreach ($weights as $key => $weight) {
            $rand -= $weight;
            if ($rand <= 0) {
                return $key;
            }
        }

        return array_key_first($weights); // fallback, should never be reached
    }
}


//TODO:add stated_date_time column to developer_task_assignments table


/**
 * ======================================================================
 * DEVELOPER TASK ASSIGNMENT SEEDING RULES
 * ======================================================================
 *
 * Governs how rows in `developer_task_assignments` are generated for
 * existing `tasks`, `projects`, and `developer_project_enrollments`.
 * This seeder only INSERTS into developer_task_assignments - it never
 * creates projects, tasks, or enrollments.
 *
 * ASSUMPTION: task hierarchy is at most 1 level deep - a task can have
 * children, but a child can never have children of its own. All "parent / child" rules below rely on that.
 *
 *
 * 1. ROW STATES (what a single developer_task_assignments row can be)
 * ----------------------------------------------------------------------
 * Every row is in exactly one of these four states:
 *
 *   a) ASSIGNED, COMPLETED
 *        - assigned_date_time  != null
 *        - finished_date_time  != null
 *        - spend_time          != null
 *        - developer_project_enrollment_id != null
 *        - progress = 'completed'
 *		  - stopped_date_time = null
 *
 *   b) ASSIGNED, CANCELLED
 *        - assigned_date_time  != null
 *        - finished_date_time  = null
 *        - spend_time          != null   (time spent before cancelling)
 *        - developer_project_enrollment_id != null
 *        - progress = 'cancelled'
 *        - stopped_date_time != null
 *
 *   c) ASSIGNED, NOT FINISHED
 *        - assigned_date_time  != null
 *        - finished_date_time  = null
 *        - spend_time          = null
 *        - developer_project_enrollment_id != null
 *        - progress = 'not_started' | 'in_progress' | 'blocked'
 *        - stopped_date_time = null
 *
 * 
 * NOT ASSIGNED (individual tasks -> task that has no parent or no child)
 *        - then there is no row in developer_task_assignments table
 *
 *
 * NOTIFY FLAG 
 *   is_notify = true  : this field is use to track weather it is notification is send or not to assignee
 *                     (this feature need to impliment in future)
 *
 * 2. VALUE RANGES & TIMING
 * ----------------------------------------------------------------------
 *   - spend_time, when filled, must satisfy:
 *         5 < spend_time < (5 * 8 * 60) + 1000   →   5 < spend_time < 3400
 *     (use inclusive bounds of 6..3399 in code so the inequality stays strict)
 *
 *   - finished_date_time, when filled, must satisfy:
 *         assigned_date_time + 5 minutes < finished_date_time < assigned_date_time + 2 weeks
 *     (use 6..(2 weeks - 1 minute) in code so the inequality stays strict)
 *
 *   - assigned_date_time, when filled, must be strictly AFTER all of:
 *         project.start_at
 *         developer_project_enrollment.assigned_date_time
 *         task.created_at
 *     (push the lower bound forward by at least 1 minute before picking a
 *     random value, otherwise a random pick can land exactly on the bound)
 *
 *   - developer_task_assignments.created_at = developer_task_assignments.assigned_date_time  
 *
 *   - developer_task_assignments.task_id's project must equal
 *     developer_task_assignments.developer_project_enrollment_id's project
 *     (only checked when developer_project_enrollment_id is not null).
 *
 *
 * 3. HOW MANY DEVELOPERS PER TASK
 * ----------------------------------------------------------------------
 * This distribution only applies to a task that is assigned on its own
 * (a standalone/leaf task, or a child assigned individually under Mode B
 * below). A parent task's own assignment (Mode A) always uses exactly ONE
 * developer for the whole family - the split below does not apply there.
 *
 *   - 85% of the time  -> 1 developer  (1 row for that task_id)
 *   - 10% of the time  -> 2 developers (2 rows, same task_id, different developer_project_enrollment_id)
 *   -  5% of the time  -> 3 developers (3 rows, same task_id, different developer_project_enrollment_id)
 *
 * Each of those rows is independent: they can each land in any of the three ROW STATES from section 1, so 
 * a task with multiple developers can end up "mixed" (e.g. one developer completed it, another is still in_progress).
 *
 *
 * 4. PARENT / CHILD ASSIGNMENT
 * ----------------------------------------------------------------------
 *
 * 4.1 Task has NO children
 *     - Assigned independently, following sections 1-3 above.
 *
 * 4.2 Task HAS children
 *     Parent-level and child-level assignment are mutually exclusive -
 *     exactly one of the two modes below applies to the whole family:
 *
 *     MODE A - parent is assigned (children forced to follow)
 *       - Parent gets ONE developer (one enrollment) and its own assigned_date_time.
 *       - Every child is forced onto that SAME developer and the SAME
 *         assigned_date_time as the parent (never a different developer,
 *         never the 1-3 developer split from section 3).
 *       - Each child still independently rolls its own outcome (completed /
 *         cancelled / not finished), so children can finish at different
 *         times or not finish at all.
 *       - The parent's row is then derived from its children:
 *           finished_date_time = latest child finished_date_time,
 *                                 but ONLY if every child is finished (progress = 'completed'); otherwise null
 *           spend_time         = sum of every child's spend_time
 *           progress           = the shared value if every child has the same progress, otherwise 'mixed'
 *
 *     MODE B - parent is NOT assigned (children decide individually)
 *       - Parent's own row: developer_project_enrollment_id = null, assigned_date_time = null. 
 *         This row ALWAYS exists (unlike a plain unassigned standalone task), because it still needs to
 *         carry the aggregated state described below.
 *       - Each child is assigned independently: it may be unassigned, or
 *         assigned to 1-3 developers per the section 3 distribution. "Any
 *         number of children can assign" includes zero - the whole family
 *         can end up completely unassigned.
 *       - Before aggregating, reduce each child down to one summary,
 *         since a child itself may carry 0-3 rows:
 *         ---------------------------------------------------------------------------------------------
 *           - no rows at all       ->  treat as progress = 'not_started', finished = null, spend = null
 *         ---------------------------------------------------------------------------------------------  
 *           - 1+ rows, same progress on all of them -> use that progress; finished = latest, 
 *                                      but only if every one of that child's own rows finished; spend = sum of that child's rows
 *         ---------------------------------------------------------------------------------------------  
 *           - 1+ rows, mixed progress across them -> progress = 'mixed'(finished / spend computed the same way as above)
 *         ---------------------------------------------------------------------------------------------  
 *       - Then aggregate across children exactly as in Mode A:
 *           finished_date_time = latest child summary's finished date,only if every child summary is finished
 *           spend_time         = sum of every child summary's spend_time
 *           progress           = the shared value if every child has the same progress, otherwise 'mixed'
 * 
 */








/*
==when task assigned and  finished==
assigned_date_time != null then finished_date_time != null
spend_time!=null
developer_project_enrollment_id != null
progress= completed
stopped_date_time = null

==when task assigned and  cancelled==
assigned_date_time != null then finished_date_time = null
spend_time!=null
developer_project_enrollment_id != null
progress= cancelled
stopped_date_time != null

==when task assigned and not finished==
assigned_date_time != null then finished_date_time = null
spend_time=null
developer_project_enrollment_id != null
progress= not_started/in_progress/blocked
stopped_date_time = null

is_notify=true


=when fill spend time =
5 < spend_time < 5*8*60(2400) + 1000

=task assign count=
developer_task_assignments.task_id   (85% assign task for 1 user)
developer_task_assignments.task_id   (10% assign task for 2 user)
developer_task_assignments.task_id   (5% assign task for 3 user)

developer_task_assignments.task_id->project = developer_task_assignments.developer_project_enrollment_id->project  

task.create_at < developer_task_assignments.assigned_date_time 

developer_task_assignments.created_at = developer_task_assignments.assigned_date_time

in here usually (apply this) 
task.assigned_date_time+ 5 minutes < task.finished_date_time < task.assigned_date_time + 2 weeks



=task assign=
1.if tasks that has no child then task can assign
    when task has child tasks both parent and childs cannot assign 
    either parent assign and childs are not assign 
    or
    parent not assign and any numebre of childs can assign 

2. when parent assign and childs are not assign 
    then child task also automatically assign to that developer also 
    (all child task developer_project_enrollment_id = parent task developer_project_enrollment_id) 
    then all child task assigned_date_time = parent task assigned_date_time 
    if all child tasks are finished  then  parent task finished_date_time = latest finished_date_time among the child taks 
    otherwise parent task finished_date_time=null
    parent task spend_time should equal to sum of child task spend_times 
    parent task progress 
        if all child task progress = not_started then parent task progress=not_started 
        if all child task progress = in_progress then parent task progress=in_progress 
        if all child task progress = completed then parent task progress=completed 
        if all child task progress = blocked then parent task progress=blocked 
        if all child task progress = cancelled then parent task progress=cancelled 
        else parent task progress=mixed 

3. parent not assign and any numebre of childs can assign 
    (parent task developer_project_enrollment_id = null and then child tasks developer_project_enrollment_id can have value if they assigned unless null) 

    (parent task assigned_date_time = null and then child tasks assigned_date_time can have value if they assigned unless null) 

    if all child tasks are finished  then  parent task finished_date_time = latest finished_date_time among the child taks 
    otherwise parent task finished_date_time=null
    parent task spend_time should equal to sum of child task spend_times  
    parent task progress 
        if all child task progress = not_started then parent task progress=not_started 
        if all child task progress = in_progress then parent task progress=in_progress 
        if all child task progress = completed then parent task progress=completed 
        if all child task progress = blocked then parent task progress=blocked 
        if all child task progress = cancelled then parent task progress=cancelled 
        else parent task progress=mixed
*/