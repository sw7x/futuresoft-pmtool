<?php
namespace Modules\Communicate\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon as SupportCarbon;
use Faker\Factory as FakerFactory;

use App\Models\Role;
use App\Models\User;
use Modules\Communicate\Models\ProjectThread;
use Modules\Communicate\Models\ProjectThreadMessage;
use Modules\Project\Models\DeveloperProjectEnrollment;
use Modules\Project\Models\Project;

class ProjectThreadSeeder extends Seeder
{
    /**
     * Role slugs that may post a thread / message on ANY project,
     * with no enrollment requirement.
     */
    private const STAFF_ROLE_SLUGS = [
        Role::ADMIN,
        Role::OWNER,
        Role::MANAGER,
        Role::PROJECT_MANAGER,
    ];

    /** Min/max number of threads created per project. */
    private const THREADS_PER_PROJECT_MIN = 2;
    private const THREADS_PER_PROJECT_MAX = 6;

    /** Min/max number of messages per thread (includes the opening message). */
    private const MESSAGES_PER_THREAD_MIN = 1;
    private const MESSAGES_PER_THREAD_MAX = 10;

    public function run(): void
    {
        $faker = FakerFactory::create();

        // ---------------------------------------------------------------
        // 1) Users allowed to post ANYWHERE (admin / owner / manager / pm)
        // ---------------------------------------------------------------
        $staffUserIds = User::whereHas('roles', function ($q) {
                $q->whereIn('slug', self::STAFF_ROLE_SLUGS);
            })
            ->where('employment_status', 'active')
            ->pluck('id')
            ->all();

        if (empty($staffUserIds)) {
            $this->command->warn(
                'No users with admin/owner/manager/project_manager roles found. '
                . 'Project threads cannot be seeded without at least one eligible poster.'
            );
            return;
        }

        // ---------------------------------------------------------------
        // 2) Users who hold the developer role (candidates - still need to
        //    be enrolled to a specific project, and the post must happen
        //    AFTER their enrollment date, to be allowed to post there)
        // ---------------------------------------------------------------
        $developerRoleUserIds = User::whereHas('roles', function ($q) {
                $q->where('slug', Role::DEVELOPER);
            })
            ->where('employment_status', 'active')
            ->pluck('id')
            ->all();
        $developerRoleUserIds = array_flip($developerRoleUserIds); // fast isset() lookups

        Project::query()->orderBy('id')->chunk(50, function ($projects) use ($faker, $staffUserIds, $developerRoleUserIds) {
            foreach ($projects as $project) {
                $this->seedThreadsForProject($project, $staffUserIds, $developerRoleUserIds, $faker);
            }
        });
    }


    
    private function seedThreadsForProject(
        Project $project,
        array $staffUserIds,
        array $developerRoleUserIds,
        $faker
    ): void {
        // -----------------------------------------------------------
        // Build the poster pool for this project.
        // Each entry tracks a `not_before` timestamp:
        //   - null            => staff user, postable at any time
        //   - Carbon instance => developer, postable only STRICTLY AFTER their earliest enrollment date
        //                        for this project (assigned_date_time)
        // -----------------------------------------------------------
        $pool = [];
        foreach ($staffUserIds as $id) {
            $pool[] = ['id' => $id, 'not_before' => null];
        }

        $earliestEnrollmentByDeveloper = DeveloperProjectEnrollment::where('project_id', $project->id)
            ->selectRaw('developer_id, MIN(assigned_date_time) as first_enrolled_at')
            ->groupBy('developer_id')
            ->pluck('first_enrolled_at', 'developer_id');

        foreach ($earliestEnrollmentByDeveloper as $developerId => $firstEnrolledAt) {
            if (!isset($developerRoleUserIds[$developerId])) {
                continue; // must still hold the developer role
            }
            $pool[] = [
                'id'         => $developerId,
                'not_before' => Carbon::parse($firstEnrolledAt),
            ];
        }

        if (empty($pool)) {
            // No one is eligible to post on this project - skip it.
            return;
        }

        $threadCount = rand(self::THREADS_PER_PROJECT_MIN, self::THREADS_PER_PROJECT_MAX);

        for ($i = 0; $i < $threadCount; $i++) {
            $threadCreatedAt = $this->generateThreadCreatedAt($project, $faker);
            $postedBy = $this->pickEligiblePoster($pool, $threadCreatedAt, $faker, $staffUserIds);

            $thread = ProjectThread::create([
                'title'      => $faker->optional(0.9)->sentence(rand(3, 7)),
                'status'     => $faker->boolean(90) ? 'enable' : 'disable',
                'project_id' => $project->id,
                'posted_by'  => $postedBy,
                'created_at' => $threadCreatedAt,
                'updated_at' => $threadCreatedAt,
            ]);

            $this->seedMessagesForThread($thread, $threadCreatedAt, $pool, $staffUserIds, $faker);
        }
    }

    /**
     * 90% of threads are opened AFTER the project officially started
     * (project.start_at < thread.created_at).
     * The remaining 10% are opened before/at kickoff, e.g. planning
     * discussions started while the project record was being set up
     * (project.start_at >= thread.created_at).
     */
    private function generateThreadCreatedAt(Project $project, $faker): Carbon
    {
        $startAt = $project->start_at
            ? Carbon::parse($project->start_at)
            : Carbon::now()->subMonths(6);

        if ($faker->boolean(90)) {
            // Strictly after start_at, up to "now".
            $upperBound = $startAt->copy()->greaterThan(Carbon::now())
                ? $startAt->copy()->addMonths(3)
                : Carbon::now();

            $candidate = Carbon::instance($faker->dateTimeBetween($startAt, $upperBound));

            // Guarantee strict inequality even if faker returns start_at itself.
            if ($candidate->lessThanOrEqualTo($startAt)) {
                $candidate = $startAt->copy()->addSeconds(rand(1, 3600));
            }

            return $candidate;
        }

        // start_at >= created_at (pre-kickoff thread)
        return Carbon::instance(
            $faker->dateTimeBetween($startAt->copy()->subMonths(2), $startAt)
        );
    }

    /**
     * Picks a random poster who is allowed to post AT (or as of) the given
     * timestamp:
     *   - staff users (not_before === null) are always eligible
     *   - developers are only eligible if their earliest project
     *     enrollment date is STRICTLY before $at
     *     (developer project enrolled time < developer message post time)
     *
     * Falls back to a staff user if nobody in the pool qualifies yet
     * (e.g. a thread/message timestamp that predates every developer's
     * enrollment) - staff always exist because run() bails out early
     * otherwise.
     */
    private function pickEligiblePoster(array $pool, Carbon $at, $faker, array $staffUserIds): int
    {
        $eligible = array_values(array_filter($pool, function ($entry) use ($at) {
            return $entry['not_before'] === null || $entry['not_before']->lessThan($at);
        }));

        if (empty($eligible)) {
            return (int) $faker->randomElement($staffUserIds);
        }

        return (int) $faker->randomElement($eligible)['id'];
    }

    private function seedMessagesForThread(
        ProjectThread $thread,
        Carbon $threadCreatedAt,
        array $pool,
        array $staffUserIds,
        $faker
    ): void {
        $messageCount = rand(self::MESSAGES_PER_THREAD_MIN, self::MESSAGES_PER_THREAD_MAX);

        // id => posted_date_time, kept in insertion (chronological) order.
        $postedMessageIds = [];

        $cursor = $threadCreatedAt->copy();

        for ($i = 0; $i < $messageCount; $i++) {
            if ($i === 0) {
                // The earliest message in the thread: fixed rules.
                $postedDateTime = $threadCreatedAt->copy();
                $postedBy = $thread->posted_by;
                $repliedTo = null;
            } else {
                $cursor = $cursor->copy()->addMinutes(rand(5, 4320)); // up to ~3 days later
                $postedDateTime = $cursor->copy();
                $postedBy = $this->pickEligiblePoster($pool, $postedDateTime, $faker, $staffUserIds);

                // 55% chance of replying to an earlier message in this same thread.
                $repliedTo = null;
                if (!empty($postedMessageIds) && $faker->boolean(55)) {
                    $repliedTo = $faker->randomElement(array_keys($postedMessageIds));
                }
            }

            $message = ProjectThreadMessage::create([
                'message'               => $faker->paragraphs(rand(1, 3), true),
                'posted_date_time'      => $postedDateTime,
                'project_thread_id'     => $thread->id,
                'replied_to_message_id' => $repliedTo,
                'posted_by'             => $postedBy,
            ]);

            $postedMessageIds[$message->id] = $postedDateTime;
        }
    }

}


/**
 * ============================================================
 *  SEEDER RULES — Project Threads & Project Thread Messages
 * ============================================================
 *
 * ------------------------------------------------------------
 * 1. project_threads
 * ------------------------------------------------------------
 *
 * a) Timing (relative to project.start_at):
 *    - 90% of threads: project.start_at  <  project_threads.created_at (thread opened AFTER the project started)
 *    - 10% of threads: project.start_at  >= project_threads.created_at (thread opened BEFORE / AT the project's start)
 *
 * b) Who can be `posted_by`:
 *    - Any user with role: admin, owner, manager, or project_manager -> allowed on ANY project, no restriction.
 *    - A user with the developer role -> allowed ONLY if that user is enrolled to the project
 *      (developer_project_enrollments row exists for that project).
 *
 * ------------------------------------------------------------
 * 2. project_thread_messages
 * ------------------------------------------------------------
 *
 * a) First message of each thread (earliest posted_date_time):
 *    - replied_to_message_id  = NULL
 *    - posted_date_time       = project_threads.created_at
 *    - posted_by              = project_threads.posted_by
 *
 * b) Rules for replied_to_message_id (on every message):
 *    - Must reference a message from the SAME thread. (Cannot reply to a message in a different project thread.)
 *    - Must reference a message posted BEFORE the current one.(Cannot reply to a future message or to itself.)
 *
 * c) Who can be `posted_by`:
 *    - Any user with role: admin, owner, manager, or project_manager -> allowed on ANY project, no restriction.
 *    - A user with the developer role -> allowed ONLY if that user is enrolled to the project
 *      (developer_project_enrollments row exists for that project).
 * 
 * d) Timing constraint for developers posting a message:
 *    - developer_project_enrollments.assigned_date_time (enrolled time) MUST be < project_thread_messages.posted_date_time (post time)
 *    - i.e. a developer can only post a message AFTER they were enrolled to the project, never before.
 *
 * ============================================================
 */




/*
for this tables i want to create seeder that seed project threads and also their messages



Schema::create('project_threads', function (Blueprint $table) {
    $table->id();
    
    // Thread metadata
    $table->string('title')->nullable();
    $table->enum('status', ['enable', 'disable'])->default('enable');
    
    // Relationships
    $table->foreignId('project_id')->constrained('projects');
    $table->foreignId('posted_by')->constrained('users');
    
    // Timestamps
    $table->timestamps();

    $table->softDeletes();
  
});


when project threads consider these things in seeder file
    project.start_at < project_threads.created_at (90% chance)
    project.start_at >= project_threads.created_at (90% chance)






for each thread have many messages
Schema::create('project_thread_messages', function (Blueprint $table) {
    $table->id();
    
    // Message content
    $table->text('message');
    $table->timestamp('posted_date_time')->useCurrent();
    
    // Thread relationship (required)
    $table->foreignId('project_thread_id')->constrained('project_threads');
    
    // Self-referencing for replies (optional)
    $table->foreignId('replied_to_message_id')->nullable()->constrained('project_thread_messages');
    
    // Who posted this message
    $table->foreignId('posted_by')->constrained('users');
});




when project thread message consider these things in seeder file

    project_threads 1st message(poset most earlier) have replied_to_message_id=null
    project_threads 1st message(poset most earlier) posted_date_time = project_threads.created_at   
    project_threads 1st message(poset most earlier) posted_by = project_threads.posted_by



    when settting replied_to_message_id consider below
        A message can only reply to a message inside the same thread. It cannot reply to a message in a completely different project thread.

        A message can only reply to a message that was posted before it. It can never reply to a message from the future or to itself.





for below posted_by can be any user that have these roles admin,manager,owner, project_manager
for users with developer role only can be if that user is enrolled to project

project_threads.posted_by
project_thread_messages.posted_by




when developer posted a message to thread developer project enrolled time < developer message post time


*/