<?php
namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Modules\Communicate\Models\TaskAssignmentMessage;
use Modules\Project\Models\DeveloperProjectEnrollment;
use Modules\Task\Models\DeveloperTaskAssignment;

use Faker\Factory as FakerFactory;
use Faker\Generator as Faker;

/**
 * Seeds task_assignment_messages for developer_task_assignments that already exist.
 *
 * This seeder does NOT create projects, tasks, developer_project_enrollments or
 * developer_task_assignments. It only reads them and attaches messages to them.
 *
 * RULES APPLIED:
 *   - A task assignment can have 0 or more messages.
 *   - The first message's posted_date_time is after the assignment's assigned_date_time. 
 *     Every following message is posted after the previous one (messages are in chronological order).
 *   - posted_date_time and created_at are always the same value.
 *   - is_edited is false 90% of the time.
 *   - posted_by is either one of the "management" roles (admin, owner,manager, pm) or 
 *     one of the developers enrolled on the assignment's project.
 */

class TaskAssignmentMessageSeeder extends Seeder
{
    // ==================== TUNABLE CONFIG ====================

    // How many messages a single assignment can get, and how likely each
    // amount is. 0 => no conversation happened on that assignment at all.
    private const MESSAGE_COUNT_WEIGHTS = [
        0 => 40,
        1 => 20,
        2 => 15,
        3 => 10,
        4 => 8,
        5 => 4,
        6 => 3,
    ];

    // Chance a message is marked as edited.
    private const IS_EDITED_PROBABILITY = 0.10;

    // Gap between two consecutive messages (minutes).
    private const MIN_GAP_MINUTES = 5;
    private const MAX_GAP_MINUTES = 60 * 24 * 3; // up to 3 days between replies

    // Role names that are always allowed to post on any assignment,
    // regardless of the project. Adjust to match your role system.
    private const MANAGEMENT_ROLES = ['admin', 'owner', 'manager', 'pm'];
    
    /**
     * Faker instance
     */
    protected Faker $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }
    




    public function run(): void
    {
        // Users who can post on ANY assignment (admin / owner / manager / pm).
        $managementUsers = $this->getManagementUsers();

        DeveloperTaskAssignment::with('task')->chunk(200, function (Collection $assignments) use ($managementUsers) {
            foreach ($assignments as $assignment) {
                $this->seedMessagesForAssignment($assignment, $managementUsers);
            }
        });
    }

    // ==================== PER ASSIGNMENT ====================

    private function seedMessagesForAssignment(DeveloperTaskAssignment $assignment, Collection $managementUsers): void
    {
        // We need a starting point in time to post messages after - if the
        // assignment was never actually assigned, there is nothing to base
        // the conversation on, so skip it.
        if (!$assignment->assigned_date_time) {
            return;
        }

        $messageCount = $this->weightedRandomKey(self::MESSAGE_COUNT_WEIGHTS);

        if ($messageCount === 0) {
            return; // no conversation on this assignment
        }

        // Pool of people allowed to write in this assignment's thread:
        // management users + developers enrolled on the same project.
        $posters = $managementUsers
            ->merge($this->getProjectDeveloperUsers($assignment))
            ->unique('id');

        if ($posters->isEmpty()) {
            return; // nobody available to post a message
        }

        $lastPostedAt = Carbon::parse($assignment->assigned_date_time);

        for ($i = 0; $i < $messageCount; $i++) {
            $lastPostedAt = $this->randomTimeAfter($lastPostedAt);

            TaskAssignmentMessage::create([
                'task_assignment_id' => $assignment->id,
                'message' => $this->faker->sentence(random_int(6, 20)),
                'posted_date_time' => $lastPostedAt,
                'posted_by' => $posters->random()->id,
                'is_edited' => $this->rollProbability(self::IS_EDITED_PROBABILITY),
                'created_at' => $lastPostedAt, // created_at mirrors posted_date_time
                'updated_at' => $lastPostedAt,
            ]);
        }
    }

    // ==================== POSTER HELPERS ====================

    /**
     * Users with one of the "management" roles - allowed to post anywhere.
     * Uses spatie/laravel-permission style roles(); adjust this method if
     * your project uses a different role system.
     */
    private function getManagementUsers(): Collection
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', self::MANAGEMENT_ROLES);
        })->get();
    }

    /**
     * Developers enrolled on the same project as this assignment's task.
     */
    private function getProjectDeveloperUsers(DeveloperTaskAssignment $assignment): Collection
    {
        $projectId = $assignment->task->project_id ?? null;

        if (!$projectId) {
            return collect();
        }

        $developerIds = DeveloperProjectEnrollment::where('project_id', $projectId)
            ->pluck('developer_id');

        return User::whereIn('id', $developerIds)->get();
    }

    // ==================== DATE HELPER ====================

    private function randomTimeAfter(Carbon $after): Carbon
    {
        return $after->copy()->addMinutes(
            random_int(self::MIN_GAP_MINUTES, self::MAX_GAP_MINUTES)
        );
    }

    // ==================== RANDOM HELPERS ====================

    private function rollProbability(float $probability): bool
    {
        return (mt_rand() / mt_getrandmax()) < $probability;
    }

    /**
     * Picks a key from a ['key' => weight] array, weighted by the given weights.
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


/*
not that 1 task assignment can have 0 or more task_assignment_messages

task_assignment_messages.is_edited = false (90% chance)



task_assignment.assigned_date_time 
    < (first message of task assignment ) task_assignment_messages.posted_date_time  < task_assignment other messages posted_date_time 

task_assignment_messages.posted_date_time = task_assignment_messages.create_at


=task_assignment_messages.posted_by= 
    admin, owner, manager, pm , project enrolled devs

*/