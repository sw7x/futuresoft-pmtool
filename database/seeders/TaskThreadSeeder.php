<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon as SupportCarbon;
use Faker\Factory as FakerFactory;

use App\Models\Role;
use App\Models\User;
use Modules\Task\Models\Task;
use Modules\Communicate\Models\TaskThread;
use Modules\Communicate\Models\TaskThreadMessage;
use Modules\Project\Models\DeveloperProjectEnrollment;
use Modules\Project\Models\Project;




class TaskThreadSeeder extends Seeder
{
    /**
     * Role slugs that may post a thread / message on ANY task,
     * with no enrollment requirement.
     */
    private const STAFF_ROLE_SLUGS = [
        Role::ADMIN,
        Role::OWNER,
        Role::MANAGER,
        Role::PROJECT_MANAGER,
    ];

    /** Min/max number of threads created per task. */
    private const THREADS_PER_TASK_MIN = 1;
    private const THREADS_PER_TASK_MAX = 3;

    /** Min/max number of messages per thread (includes the opening message). */
    private const MESSAGES_PER_THREAD_MIN = 1;
    private const MESSAGES_PER_THREAD_MAX = 4;

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
                . 'Task threads cannot be seeded without at least one eligible poster.'
            );
            return;
        }

        // ---------------------------------------------------------------
        // 2) Users who hold the developer role (candidates - still need to
        //    be enrolled to a specific project to be allowed to post on
        //    that project's tasks).
        // ---------------------------------------------------------------
        $developerRoleUserIds = User::whereHas('roles', function ($q) {
                $q->where('slug', Role::DEVELOPER);
            })
            ->where('employment_status', 'active')
            ->pluck('id')
            ->all();
        $developerRoleUserIds = array_flip($developerRoleUserIds); // fast isset() lookups

        Project::query()
            ->with('tasks') // Eager load tasks to avoid N+1 queries
            ->orderBy('id')
            ->chunk(10, function ($projects) use ($faker, $staffUserIds, $developerRoleUserIds) {
                foreach ($projects as $project) {

                    // Check if the project has no tasks
                    if ($project->tasks->isEmpty()) {
                        // Optional: Handle projects with no tasks (e.g., log, seed default task, or skip)
                        // $this->command->info("Project ID {$project->id} has no tasks. Skipping...");
                        continue;
                    }

                    // Everyone allowed to post on this project: staff (always)
                    // plus any developer who is enrolled on the project, no
                    // matter which task or when they were assigned to it.
                    $posterPool = $this->getProjectPosterPool($project, $staffUserIds, $developerRoleUserIds);

                    if (empty($posterPool)) {
                        // No one is eligible to post on this project - skip it.
                        continue;
                    }

                    // Loop through tasks safely
                    foreach ($project->tasks as $task) {
                        $this->seedThreadsForTask($task, $posterPool, $faker);
                    }
                }
            });
    }

    /**
     * Everyone who may post (thread or message) on any task belonging to
     * this project:
     *   - staff (admin / owner / manager / project_manager) - always.
     *   - developers who are enrolled on this project and still hold the
     *     developer role. Enrollment is the ONLY requirement - which task
     *     it is, or when the developer was enrolled, does not matter.
     *
     * @return array<int, int> pool of eligible user ids
     */
    private function getProjectPosterPool(Project $project, array $staffUserIds, array $developerRoleUserIds): array
    {
        $enrolledDeveloperIds = DeveloperProjectEnrollment::where('project_id', $project->id)
            ->pluck('developer_id')
            ->unique()
            ->filter(fn ($id) => isset($developerRoleUserIds[$id])) // must still hold the developer role
            ->values()
            ->all();

        return array_values(array_unique(array_merge($staffUserIds, $enrolledDeveloperIds)));
    }


    private function seedThreadsForTask(Task $task, array $posterPool, $faker): void
    {
        $threadCount = rand(self::THREADS_PER_TASK_MIN, self::THREADS_PER_TASK_MAX);

        for ($i = 0; $i < $threadCount; $i++) {
            $threadCreatedAt = $this->generateThreadCreatedAt($task, $faker);
            $postedBy = $faker->randomElement($posterPool);

            $thread = TaskThread::create([
                'title'      => $faker->optional(0.9)->sentence(rand(3, 7)),
                'status'     => $faker->boolean(90) ? 'enable' : 'disable',
                'task_id'    => $task->id,
                'posted_by'  => $postedBy,
                'created_at' => $threadCreatedAt,
                'updated_at' => $threadCreatedAt,
            ]);

            $this->seedMessagesForThread($thread, $threadCreatedAt, $posterPool, $faker);
        }
    }

    /**
     * 90% of threads are opened AFTER the task officially started (task.created_at < thread.created_at).
     * The remaining 10% are opened before/at kickoff, e.g. planning discussions started while the task record was being set up
     * (task.created_at >= thread.created_at).
     */
    private function generateThreadCreatedAt(Task $task, $faker): Carbon
    {
        $createdAt = $task->created_at;

        if ($faker->boolean(90)) {
            // Strictly after start_at, up to "now".
            $upperBound = $createdAt->copy()->greaterThan(Carbon::now())
                ? $createdAt->copy()->addMonths(3)
                : Carbon::now();

            $candidate = Carbon::instance($faker->dateTimeBetween($createdAt, $upperBound));

            // Guarantee strict inequality even if faker returns start_at itself.
            if ($candidate->lessThanOrEqualTo($createdAt)) {
                $candidate = $createdAt->copy()->addSeconds(rand(1, 3600));
            }

            return $candidate;
        }

        // start_at >= created_at (pre-kickoff thread)
        return Carbon::instance(
            $faker->dateTimeBetween($createdAt->copy()->subMonths(2), $createdAt)
        );
    }


    private function seedMessagesForThread(
        TaskThread $thread,
        Carbon $threadCreatedAt,
        array $posterPool,
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
                $postedBy = $faker->randomElement($posterPool);

                // 55% chance of replying to an earlier message in this same thread.
                // array_keys($postedMessageIds) only ever contains messages
                // already created above, so a reply always points to a message
                // in the same thread that was posted earlier - never itself,
                // never the future, never another thread.
                $repliedTo = null;
                if (!empty($postedMessageIds) && $faker->boolean(55)) {
                    $repliedTo = $faker->randomElement(array_keys($postedMessageIds));
                }
            }

            $message = TaskThreadMessage::create([
                'message'               => $faker->paragraphs(rand(1, 3), true),
                'posted_date_time'      => $postedDateTime,
                'task_thread_id'        => $thread->id,
                'replied_to_message_id' => $repliedTo,
                'posted_by'             => $postedBy,
            ]);

            $postedMessageIds[$message->id] = $postedDateTime;
        }
    }
}

/**
 * =====================================================
 * SEEDER RULES: Task Threads & Task Thread Messages
 * =====================================================
 *
 * -----------------------------------------------------
 * 1. Task Thread Creation Date
 * -----------------------------------------------------
 * When generating `task_threads.created_at` relative to `task.created_at`,
 * use the following probability split:
 *
 *   - 90% chance: task.created_at  < task_threads.created_at(thread created after the task)
 *   - 10% chance: task.created_at >= task_threads.created_at(thread created at/before the task)
 *
 *   NOTE: Original spec listed both cases at "90% chance," which is invalid since probabilities 
 *   must sum to 100%. Adjusted the second case to 10% (mostly-after-task behavior). Update the split if a
 *   different ratio was actually intended.
 *
 * -----------------------------------------------------
 * 2. First Message in a Task Thread
 * -----------------------------------------------------
 * The earliest message in each task thread must mirror the thread itself:
 *
 *   - replied_to_message_id : null
 *   - posted_date_time      : same as task_threads.created_at
 *   - posted_by             : same as task_threads.posted_by
 *
 * -----------------------------------------------------
 * 3. Rules for `replied_to_message_id` (all other messages)
 * -----------------------------------------------------
 *   - Must reference a message from the SAME task thread only (no cross-thread replies).
 *   - Must reference a message posted BEFORE it (no replying to itself or to a future message).
 *
 * -----------------------------------------------------
 * 4. Who Can Post Messages (posted_by)
 * -----------------------------------------------------
 *   - Users with role: admin, manager, owner, or project_manager
 *     → can post at any time, no restrictions.
 *
 *   - Users with role: developer
 *     → can only post if they are enrolled in the project.
 * 
 * -----------------------------------------------------
 * 5. Skipped Checks
 * -----------------------------------------------------
 *  - when developer posted a message to task thread developer task assigned time < developer message post time
 * 
 */


/*
=task_threads check=
    task.created_at < task_threads.created_at (90% chance)
    task.created_at >= task_threads.created_at (90% chance)


=task_thread_messages check=
when task thread message consider these things in seeder file

    task_threads 1st message(poset most earlier) have replied_to_message_id=null
    task_threads 1st message(poset most earlier) posted_date_time = task_threads.created_at   
    task_threads 1st message(poset most earlier) posted_by = task_threads.posted_by

    when settting replied_to_message_id consider below
        A message can only reply to a message inside the same thread. It cannot reply to a message in a completely different task thread.
        A message can only reply to a message that was posted before it. It can never reply to a message from the future or to itself.

for below posted_by can be any user that have these roles admin,manager,owner, project_manager
for users with developer role only can be if that user is enrolled to project

XXXX ----- when developer posted a message to task thread developer task assigned time < developer message post time
*/
