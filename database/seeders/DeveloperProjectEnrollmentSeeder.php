<?php
namespace Database\Seeders;

use Modules\Project\Models\DeveloperProjectEnrollment;
use Modules\Project\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class DeveloperProjectEnrollmentSeeder extends Seeder
{
    /**
     * How many enrollment records to create.
     */
    protected int $count = 300;

    public function run(): void
    {
        $now = Carbon::now();
        $faker = FakerFactory::create();


        // -----------------------------------------------------------------
        // Only developers who are currently active can be assigned.
        // We need date_of_joined too, since assigned_date_time must be after it.
        // -----------------------------------------------------------------
        $developers = User::whereHas('roles', function ($query) {
                $query->where('slug', 'developer');
            })
            ->where('employment_status', 'active')
            ->get(['id', 'date_of_joined']);    


        // -----------------------------------------------------------------
        // Projects that are still "active" in some sense — skip finished
        // ones (completed) and dead ones (cancelled).
        // We also need created_at, since assigned_date_time must come after
        // the project itself was created.
        // -----------------------------------------------------------------
        $projects = Project::whereNotIn('progress', ['completed', 'cancelled'])
            ->get(['id', 'actual_delivery_at', 'created_at']);



        if ($developers->isEmpty()) {
            $this->command->warn("No active developers found. Seed users first.");
            return;
        }

        if ($projects->isEmpty()) {
            $this->command->warn("No eligible projects found (all are completed/cancelled).");
            return;
        }

        // -----------------------------------------------------------------
        // Keep track of which developer+project pairs we've already used,
        // so the same developer is never assigned twice to the same project.
        // Key format: "developerId-projectId"
        // -----------------------------------------------------------------
        $usedPairs = [];
 
        // Cap how many unique pairs actually exist, so we don't loop forever
        // trying to find a combination that doesn't exist yet.
        $maxPossiblePairs = $developers->count() * $projects->count();
        $targetCount = min($this->count, $maxPossiblePairs);
 
        $created = 0;
        $attempts = 0;
        $maxAttempts = $targetCount * 20; // safety valve against infinite loops
 
        while ($created < $targetCount && $attempts < $maxAttempts) {
            $attempts++;
 
            $developer = $developers->random();
            $project = $projects->random();
 
            $pairKey = $developer->id . '-' . $project->id;
 
            // Already assigned this developer to this project -> skip and retry
            if (isset($usedPairs[$pairKey])) {
                continue;
            }
 
            $dateOfJoined = Carbon::parse($developer->date_of_joined);
            $projectCreatedAt = Carbon::parse($project->created_at);

            // Lower bound for assigned_date_time: it must come after BOTH the
            // developer joined AND the project was created, so take the later
            // (max) of the two dates.
            $lowerBound = $dateOfJoined->greaterThan($projectCreatedAt)
                ? $dateOfJoined
                : $projectCreatedAt;
 
            // Upper bound for assigned_date_time:
            // Rule says assigned_date_time < project.actual_delivery_at, but most
            // eligible projects (not completed/cancelled) still have a NULL
            // actual_delivery_at. So when it's null, fall back to "now" as the
            // latest possible assignment date.
            $upperBound = $project->actual_delivery_at
                ? Carbon::parse($project->actual_delivery_at)
                : $now;
 
            // Safety check: if the lower bound is already after the upper bound
            // (rare edge case), this pair can never work — mark it used so we
            // don't waste further attempts retrying it, then skip.
            if ($lowerBound->gte($upperBound)) {
                $usedPairs[$pairKey] = true;
                continue;
            }
 

            // Pick a random assigned_date_time strictly between the two bounds
            $assignedDateTime = Carbon::instance(
                $faker->dateTimeBetween($lowerBound, $upperBound)
            );
 
            DeveloperProjectEnrollment::create([
                'is_notify' => true,               // always notify on assignment
                'assigned_date_time' => $assignedDateTime,
                'unassigned_date_time' => null,     // still assigned, never unassigned
                'message' =>  $faker->boolean(50) ?  $faker->sentence() : null, 
                'project_id' => $project->id,
                'developer_id' => $developer->id,
 
                'created_at' => $assignedDateTime,
                'updated_at' => $assignedDateTime,
            ]);
 
            // Mark this developer+project combination as used, and count it
            $usedPairs[$pairKey] = true;
            $created++;
        }
 
        if ($created < $this->count) {
            $this->command->info("Created {$created} enrollments (fewer than requested {$this->count} because only {$maxPossiblePairs} unique developer+project pairs exist).");
        }
    }
}
 


/**
 * =====================================================================
 * DEVELOPER PROJECT ENROLLMENT SEEDER — BUSINESS RULES
 * =====================================================================
 *
 * 1. FIXED FIELD VALUES
 * ---------------------------------------------------------------------
 * - developer_project_enrollments.is_notify            = true   (always notify on assignment)
 * - developer_project_enrollments.unassigned_date_time = null   (every seeded row is still active)
 *
 *
 * 2. DEVELOPER SELECTION (developer_id)
 * ---------------------------------------------------------------------
 * - Must be a user with role = developer
 * - Must have users.employment_status = active
 *
 *
 * 3. PROJECT SELECTION (project_id)
 * ---------------------------------------------------------------------
 * - Skip projects where projects.progress = completed
 * - Skip projects where projects.progress = cancelled
 *
 *
 * 4. DATE RULES (assigned_date_time / created_at)
 * ---------------------------------------------------------------------
 * - users.date_of_joined < developer_project_enrollments.assigned_date_time < project.actual_delivery_at
 * - project.created_at   < developer_project_enrollments.created_at  
 *      (since created_at is set equal to assigned_date_time, this meansassigned_date_time must also come after 
 *      the project's created_at — so the real lower bound is whichever is LATER: date_of_joined or project.created_at)
 *
 * 
 * 5. UNIQUENESS RULE
 * ---------------------------------------------------------------------
 * - The same developer can never be assigned to the same project more
 *   than once (no duplicate developer + project pairs)
 * =====================================================================
 */



    


/*

project.created_at < developer_project_enrollments.created_at

developer_project_enrollments.is_notify = true
developer_project_enrollments.unassigned_date_time = null

developer_id get the users that have developer role  and users.employment_status = active 


users.date_of_joined < developer_project_enrollments.assigned_date_time < project.actual_delivery_at


when select project from projects table skip project.progress = completed  project.progress = cancelled


prevent  same developer multiple time assign for a same project

*/
