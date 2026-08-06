<?php
namespace Modules\Task\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Modules\Project\Models\Project;
use Modules\Task\Models\Task;

use Faker\Factory as FakerFactory;
use Faker\Generator as Faker;


class TaskSeeder extends Seeder
{
    
    /**
     * Faker instance
     */
    protected Faker $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * Possible values for the enum columns.
     */
    private array $priorities = ['critical', 'high', 'medium', 'low'];
    private array $statuses = ['enable', 'disable', 'enable', 'enable', 'enable'];

    /**
     * Entry point. We only seed tasks — projects already exist in the DB.
     */
    public function run(): void
    {
        Project::all()->each(function (Project $project) {
            $this->createTasksForProject($project);
        });
    }

    /**
     * Create a few parent tasks (each with a chance of having subtasks)
     * for one project.
     */
    private function createTasksForProject(Project $project): void
    {
        $parentTaskCount = rand(3, 6);

        for ($i = 0; $i < $parentTaskCount; $i++) {
            $this->createParentTask($project);
        }
    }


    /**
     * Create one top-level (parent) task and, sometimes, its subtasks.
     */
    private function createParentTask(Project $project): void
    {
        // Rule: project.created_at < task.created_at
        // -> put the task's created_at sometime in the days after the project was created.
        $parentCreatedAt = Carbon::parse($project->created_at)
            ->addMinutes(rand(10, 60 * 24 * 5));

        // Rule: 15 < estimate_time (minutes) < 2400 for a parent task
        $parentEstimate = rand(16, 2399);

        // Rule: created_at + 1h < deadline < task.created_at + 2 weeks        
        $parentDeadline = (clone $parentCreatedAt)
            ->addHours(1)
            ->addMinutes(rand(60, (60 * 24 * 14) - 60));

        // Decide up front whether this parent will get subtasks, and if so,
        // whether the whole family is "enabled" or "disabled" as a group.
        // Rule: if it has subtasks -> parent enabled + at least one subtask enabled,
        //       OR parent and every subtask disabled.
        $willHaveSubtasks = rand(1, 100) <= 60; // ~60% of parents get subtasks
        $familyEnabled = $willHaveSubtasks ? (bool) rand(0, 1) : null;

        $parentStatus = $willHaveSubtasks
            ? ($familyEnabled ? 'enable' : 'disable')
            : $this->statuses[array_rand($this->statuses)];

        $parent = new Task([
            'name' => 'Parent Task - ' . $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'estimate_time' => $parentEstimate,
            'deadline' => $parentDeadline,
            'priority' => $this->priorities[array_rand($this->priorities)],
            'status' => $parentStatus,
            'parent_task_id' => null, // top-level task
            'project_id' => $project->id,
        ]);


        // created_at/updated_at aren't mass-assignable, so set them directly.
        $parent->created_at = $parentCreatedAt;
        $parent->updated_at = $parentCreatedAt;
        $parent->save();

        if ($willHaveSubtasks) {
            $this->createSubTasks($parent, $familyEnabled);
        }

        // Randomly soft-delete ~10% of parent tasks. The Task model's
        // "deleting" event automatically cascades this to its subtasks.
        if (rand(1, 100) <= 10) {
            $parent->delete();
        }
    }

    /**
     * Create 1-4 subtasks under a parent task.
     */
    private function createSubTasks(Task $parent, bool $familyEnabled): void
    {
        $subTaskCount = rand(1, 4);

        // Rule: sum of subtasks' estimate_time <= parent's estimate_time.
        // We hand out the parent's estimate budget piece by piece.
        $remainingEstimate = $parent->estimate_time;

        // Rule: at least one subtask must be enabled when the family is enabled.
        $guaranteedEnabledIndex = $familyEnabled ? rand(0, $subTaskCount - 1) : null;

        for ($i = 0; $i < $subTaskCount; $i++) {            
            
            // how many subtasks (including this) still need an estimate.
            $slotsLeft = $subTaskCount - $i;    
            
            // split what's left evenly among remaining slots, (intdiv does integer division) 
            // max(1, ...) guarantees we never try to hand out zero minutes 
            $maxForThisTask = max(1, intdiv($remainingEstimate, $slotsLeft));
            
            // picks a random amount up to that fair share, capped so it can never accidentally exceed what's actually left.
            $estimate = min($remainingEstimate, rand(1, $maxForThisTask)); 
            
            // for  the next iteration 
            $remainingEstimate -= $estimate;

            
            // Rule: parent.created_at < child.created_at
            $childCreatedAt = (clone $parent->created_at)->addMinutes(rand(1, 60 * 12));

            // Safety net: created_at must stay before the parent's deadline
            // so we can pick a valid deadline range below.
            if ($childCreatedAt->greaterThanOrEqualTo($parent->deadline)) {
                $childCreatedAt = (clone $parent->deadline)->subMinutes(30);
            }

            // Rule: subtask deadline <= parent deadline
            $childDeadline = Carbon::parse(
                $this->faker->dateTimeBetween($childCreatedAt, $parent->deadline)
            );

            $status = $familyEnabled
                ? ($i === $guaranteedEnabledIndex ? 'enable' : $this->statuses[array_rand($this->statuses)])
                : 'disable';

            $child = new Task([
                'name' => 'Sub Task - ' . $this->faker->sentence(3),
                'description' => $this->faker->paragraph(),
                'estimate_time' => $estimate,
                'deadline' => $childDeadline,
                'priority' => $this->priorities[array_rand($this->priorities)],
                'status' => $status,
                'parent_task_id' => $parent->id,
                'project_id' => $parent->project_id, // Rule: child project_id = parent's project_id
            ]);

            // created_at/updated_at aren't mass-assignable, so set them directly.
            $child->created_at = $childCreatedAt;
            $child->updated_at = $childCreatedAt;
            $child->save();
        }
    }
}


/**
 * ===========================================================
 * TASK BUSINESS RULES
 * ===========================================================
 *
 * --- Hierarchy Rules ---
 * - Only one level of nesting is allowed:
 *     - A parent task can have subtasks.
 *     - A subtask cannot have its own subtasks.
 * - If a task has no parent, its parent_task_id must be NULL.
 * - If a task has a parent, its project_id must match the parent's project_id.
 *
 * --- Timing Rules ---
 * - A task's created_at must always be after its project's created_at:
 *     project.created_at < task.created_at
 *
 * - For a PARENT task, its deadline should usually fall within:
 *     task.created_at + 1 hour  <  task.deadline  <  task.created_at + 2 weeks
 *
 * - For a SUBTASK, if its parent task has a deadline set, then:
 *     subtask.deadline <= parent_task.deadline
 *
 * - If a task has subtasks, the parent's created_at must be earlier than
 *   every subtask's created_at:
 *     parent.created_at < child.created_at
 *
 * --- Estimate Time Rules ---
 * - For a PARENT task, estimate_time (in minutes) must fall within:
 *     15 < estimate_time < 2400   (i.e. 5 days x 8 hours x 60 minutes)
 *
 * - For SUBTASKS, the sum of all subtasks' estimate_time must not exceed
 *   the parent task's estimate_time:
 *     SUM(subtask.estimate_time) <= parent.estimate_time
 *
 * --- Status Rules ---
 * - If a task has subtasks, one of the following must be true:
 *     1) Parent status = enable, AND at least one subtask status = enable
 *     2) Parent AND every subtask status = disable
 *
 * --- Soft Delete Rules ---
 * - If a parent task has subtasks and the parent is soft deleted,
 *   all of its subtasks must also be soft deleted.
 */





/*
Only one level of nesting allowed (parent tasks can have children, but children cannot have sub-children).

project.created_at < task.created_at

 
if task is parent task  :   usually task.created_at + 1 hour < tasks.deadline < task.created_at + 2 weeks
if task is sub task     :   if parent tas has deadline then : task deadline =< parent task deadline


if task is parent task  :   15 < estimate_time in munute < 5*8*60(2400)
if task is sub task  :   sum of sub tasks estimate_time <= parent estimate_time


if task has sub tasks then parent task status should be enable and at least one sub task should be enable
or
parent and sub all status should be disable


if task has parent task then child taks project_id = parent task project_id


if task has no parent tasks then parent_task_id = null


if task has child tasks  parent.created_at < child.created_at

if task has child tasks  if parent is soft deleted then all child are soft deleted

*/


