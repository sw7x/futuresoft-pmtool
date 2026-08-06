<?php
namespace Modules\Project\Database\Seeders;

use Modules\Project\Models\Client;
use Modules\Project\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

class ProjectSeeder extends Seeder
{
    /**
     * How many projects to create.
     */
    protected int $count = 200;

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
        // ---------------------------------------------------------------
        // Pull the "pools" we will pick from randomly for every project.
        // pm_id must belong to a user whose role is 'project_manager'.
        // ---------------------------------------------------------------
        
        //$pmIds = User::where('role', 'project_manager')->pluck('id');
        $pmIds = User::whereHas('roles', function ($query) { $query->where('slug', 'project_manager');})->pluck('id');

        $clientIds = Client::pluck('id');

        if ($pmIds->isEmpty()) {
            $this->command->warn("No users with role 'project_manager' found. Seed users first.");
            return;
        }

        if ($clientIds->isEmpty()) {
            $this->command->warn("No clients found. Seed clients first.");
            return;
        }

        $now = Carbon::now();

        for ($i = 0; $i < $this->count; $i++) {
            // created_at is always in the past somewhere between 2 months and 2 years ago
            $createdAt = $now->copy()->subDays(rand(60, 730))->subHours(rand(0, 23));

            // Pick which of the 4 timeline "shapes" this project follows (D/A/B/C)
            [$startAt, $plannedDeliveryAt, $actualDeliveryAt] = $this->buildTimeline($createdAt, $now);

            // deadline always sits after planned_to_delivery_at
            $deadline = $this->buildDeadline($plannedDeliveryAt, $actualDeliveryAt, $now);

            // progress depends on how the dates above turned out
            $progress = $this->determineProgress($actualDeliveryAt, $startAt, $now);

            $estimatedCost = $this->faker->numberBetween(10, 250) * 1000;

            $revenue = $this->faker->boolean(90) 
                ? $this->faker->numberBetween($estimatedCost, $estimatedCost * 2) 
                : $this->faker->numberBetween(0, $estimatedCost - 1000);                
            $revenue = round($revenue / 1000) * 1000;


            $billingType = $this->faker->randomElement(['fixed_cost', 'time_material']);
            $paymentStatus = $this->buildPaymentStatus($billingType);

            Project::create([
                'name' => $this->faker->catchPhrase(),
                'description' => $this->faker->paragraph(),

                'start_at' => $startAt,
                'planned_to_delivery_at' => $plannedDeliveryAt,
                'actual_delivery_at' => $actualDeliveryAt,
                'deadline' => $deadline,

                'currency' => 'USD',
                //'currency' => $this->faker->randomElement(['USD', 'LKR', 'EUR', 'GBP']),

                'estimated_cost' => $estimatedCost,
                'revenue' => $revenue,
                'billing_type' => $billingType,
                'payment_status' => $paymentStatus,

                'locality' => $this->faker->randomElement(['local', 'foreign']),
                'project_type' => $this->faker->randomElement(['internal', 'client', 'rd', 'maintenance']),
                'project_category' => $this->faker->randomElement(['software', 'infrastructure', 'marketing', 'hr', 'other']),
                'priority' => $this->faker->randomElement(['critical', 'high', 'medium', 'low']),
                'status' => $this->faker->randomElement(['enable', 'disable']),
                'progress' => $progress,

                'documentation' => $this->faker->boolean(70) ? $this->faker->paragraphs(3, true) : null,

                'pm_id' => $pmIds->random(),
                'client_id' => $clientIds->random(),

                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }

    /**
     * Builds [start_at, planned_to_delivery_at, actual_delivery_at] following
     * one of the 4 timeline shapes:
     *   CASE D - 10% : nothing planned yet (all three dates are null)
     *   CASE A - 30% : created_at < start_at < now() < planned_to_delivery_at
     *   CASE B - 30% : created_at < start_at < planned_to_delivery_at < actual_delivery_at < now()
     *   CASE C - 30% : created_at < start_at < planned_to_delivery_at < now(), not delivered yet
     *
     * @return array{0: ?Carbon, 1: ?Carbon, 2: ?Carbon} [start_at, planned_to_delivery_at, actual_delivery_at]
     */
    protected function buildTimeline(Carbon $createdAt, Carbon $now): array
    {
        $roll = rand(1, 100);

        if ($roll <= 10) {
            // CASE D (10%): no plan exists yet.
            // No start_at, no planned_to_delivery_at, no actual_delivery_at.
            // (deadline will also end up null, since deadline requires planned_to_delivery_at)
            return [null, null, null];
        
        }elseif ($roll <= 40) {
            // CASE A (30%): created_at < start_at < now() < planned_to_delivery_at
            // Project already started, delivery is still in the future, not yet delivered.
            $startAt = $this->between($createdAt, $now);
            $plannedDeliveryAt = $now->copy()->addDays(rand(5, 90));
            $actualDeliveryAt = null;

        } elseif ($roll <= 70) {        
            // CASE B (30%): created_at < start_at < planned_to_delivery_at < actual_delivery_at < now()
            // Project fully finished in the past.
            $startAt = $createdAt->copy()->addDays(rand(1, 30));
            $plannedDeliveryAt = $startAt->copy()->addDays(rand(10, 60));
            $actualDeliveryAt = $plannedDeliveryAt->copy()->addDays(rand(1, 30));

            // Safety clamp: make sure actual_delivery_at still lands before "now"
            if ($actualDeliveryAt->gte($now)) {
                $actualDeliveryAt = $now->copy()->subDays(rand(1, 10));
                if ($plannedDeliveryAt->gte($actualDeliveryAt)) {
                    $plannedDeliveryAt = $actualDeliveryAt->copy()->subDays(rand(1, 5));
                }
                if ($startAt->gte($plannedDeliveryAt)) {
                    $startAt = $createdAt->copy()->addDays(1);
                }
            }

        } else {
            // CASE C (30%): created_at < start_at < planned_to_delivery_at < now(), not delivered yet
            $startAt = $createdAt->copy()->addDays(rand(1, 30));
            $plannedDeliveryAt = $startAt->copy()->addDays(rand(10, 60));

            // Safety clamp: planned_to_delivery_at must still be before "now"
            if ($plannedDeliveryAt->gte($now)) {
                $plannedDeliveryAt = $now->copy()->subDays(rand(1, 10));
                if ($startAt->gte($plannedDeliveryAt)) {
                    $startAt = $createdAt->copy()->addDays(1);
                }
            }

            $actualDeliveryAt = null;
        }

        return [$startAt, $plannedDeliveryAt, $actualDeliveryAt];
    }

    /**
     * deadline is always after planned_to_delivery_at.
     * When the project already has an actual_delivery_at, 70% of the time the
     * deadline was met (deadline < actual_delivery_at) and 30% of the time it
     * was missed (deadline >= actual_delivery_at).
     *
     * Rule: when planned_to_delivery_at is not null then deadline is also not null
     * (and, by construction, vice versa: no planned date -> no deadline, CASE D).
     */
    protected function buildDeadline(?Carbon $plannedDeliveryAt, ?Carbon $actualDeliveryAt, Carbon $now): ?Carbon
    {
        // Rule: deadline can only exist if planned_to_delivery_at exists
        if ($plannedDeliveryAt === null) {
            // CASE D: no plan at all, so no deadline either
            return null;
        }

        //created_at <  start_at < now() < planned_to_delivery_at  , actual_delivery_at = null
        //created_at <  start_at  < planned_to_delivery_at < now() , actual_delivery_at = null
        if ($actualDeliveryAt === null) {
            // Nothing delivered yet, just push the deadline out after the planned date
            return $plannedDeliveryAt->copy()->addDays(rand(5, 60));
        }
        
        // created_at <  start_at < planned_to_delivery_at  < actual_delivery_at < now()
        if (rand(1, 100) <= 70) {
            // Met deadline: deadline is somewhere between planned date and actual delivery
            $diffDays = $plannedDeliveryAt->diffInDays($actualDeliveryAt);
            $offset = $diffDays > 1 ? rand(1, (int) $diffDays - 1) : 1;
            return $plannedDeliveryAt->copy()->addDays($offset);
        }

        // Missed deadline: deadline is on/after actual delivery date
        return $actualDeliveryAt->copy()->addDays(rand(0, 15));
    }

    /**
     * Decide "progress" purely from the dates we already generated.
     */
    protected function determineProgress(?Carbon $actualDeliveryAt, ?Carbon $startAt, Carbon $now): string
    {
        if ($actualDeliveryAt !== null && $actualDeliveryAt->lt($now)) {
            // Already delivered in the past -> mostly completed, sometimes cancelled
            return rand(1, 100) <= 80 ? 'completed' : 'cancelled';
        }

        if ($actualDeliveryAt !== null && $actualDeliveryAt->gt($now)) {
            // Delivery date is in the future but somehow already set (edge case, not
            // produced by our 3 timeline shapes above, but handled just in case)
            return $this->faker->randomElement(['in_progress', 'blocked', 'cancelled']);
        }

        if ($actualDeliveryAt === null && $startAt === null) {
            // Never started, no delivery date at all
            return 'in_progress';
        }


        // Covers: $actualDeliveryAt === null && $startAt !== null
        // AND acts as a safe default for the edge case actualDeliveryAt == now()
        return $this->faker->randomElement(['in_progress', 'blocked', 'cancelled']);
    }

    /**
     * fixed_cost projects only ever get invoiced fully or not at all
     * (never "partially_paid").
     */
    protected function buildPaymentStatus(string $billingType): string
    {
        if ($billingType === 'fixed_cost') {
            return $this->faker->randomElement(['not_invoiced', 'paid']);
        }

        return $this->faker->randomElement(['not_invoiced', 'partially_paid', 'paid']);
    }

    /**
     * Small helper: random datetime strictly between two Carbon instances.
     */
    protected function between(Carbon $start, Carbon $end): Carbon
    {
        if ($start->gte($end)) {
            return $start->copy()->addHour();
        }

        return Carbon::instance($this->faker->dateTimeBetween($start, $end));
    }
}



/**
 * =====================================================================
 * PROJECT SEEDER — BUSINESS RULES
 * =====================================================================
 *
 * 1. TIMELINE SHAPES (choose one per project)
 * ---------------------------------------------------------------------
 * Every project follows exactly one of these 4 date patterns:
 *
 *   CASE D (10%): No plan yet.
 *       start_at = null, planned_to_delivery_at = null, actual_delivery_at = null
 *
 *   CASE A (30%): Started, plan is in the future, not delivered.
 *       created_at < start_at < now() < planned_to_delivery_at
 *       actual_delivery_at = null
 *
 *   CASE B (30%): Fully delivered in the past.
 *       created_at < start_at < planned_to_delivery_at < actual_delivery_at < now()
 *
 *   CASE C (30%): Started, plan already passed, still not delivered.
 *       created_at < start_at < planned_to_delivery_at < now()
 *       actual_delivery_at = null
 *
 *
 * 2. DEADLINE RULES
 * ---------------------------------------------------------------------
 * - deadline is always AFTER planned_to_delivery_at.
 * - When actual_delivery_at exists:
 *     70% chance -> deadline was MET     (deadline < actual_delivery_at)
 *     30% chance -> deadline was MISSED  (deadline >= actual_delivery_at)
 *
 *
 * 3. NULLABILITY CHAIN (each depends on the one before it)
 * ---------------------------------------------------------------------
 * - actual_delivery_at NOT NULL  ->  planned_to_delivery_at NOT NULL
 * - planned_to_delivery_at NOT NULL  ->  deadline NOT NULL
 * - actual_delivery_at NOT NULL  ->  deadline NOT NULL
 * - actual_delivery_at NOT NULL  ->  start_at NOT NULL
 *
 *
 * 4. PROGRESS RULES (based on actual_delivery_at / start_at)
 * ---------------------------------------------------------------------
 * - actual_delivery_at NOT NULL and in the PAST (< now):
 *       -> completed (80%) or cancelled (20%)
 *
 * - actual_delivery_at NOT NULL and in the FUTURE (> now):
 *       -> in_progress, blocked, or cancelled (random)
 *
 * - actual_delivery_at NULL and start_at NULL:
 *       -> in_progress only
 *
 * - actual_delivery_at NULL and start_at NOT NULL:
 *       -> anything EXCEPT not_started
 *          (in_progress, blocked, or cancelled)
 *
 *
 * 5. Revenue RULES (revenue, based on progress)
 * ---------------------------------------------------------------------
 * - revenue >= estimated_cost (90% chance)
 * - revenue < estimated_cost (10% chance)
 *
 *
 * 6. BILLING / PAYMENT RULES
 * ---------------------------------------------------------------------
 * - billing_type = fixed_cost
 *       -> payment_status can only be: not_invoiced or paid
 *          (never partially_paid)
 * 
 * 
 * * 7. PROJECT MANAGER ASSIGNMENT
 * ---------------------------------------------------------------------
 * - pm_id must reference a user whose role = project_manager
 * 
 * =====================================================================
 */



/*
start_at = null, planned_to_delivery_at  = null, actual_delivery_at  = null (10% chance)
created_at <  start_at < now() < planned_to_delivery_at  , actual_delivery_at = null (30% chance)
created_at <  start_at < planned_to_delivery_at  < actual_delivery_at < now()(30% chance)
created_at <  start_at  < planned_to_delivery_at < now() , actual_delivery_at = null (30% chance)



always planned_to_delivery_at < deadline   
when actual_delivery_at is not null planned_to_delivery_at is also not null
when planned_to_delivery_at is not null then deadline is also not null
when actual_delivery_at is not null then deadline is also not null



deadline < actual_delivery_at (70% chance)
deadline >= actual_delivery_at (30% chance)


revenue >= estimated_cost (90% chance)
revenue < estimated_cost (10% chance)


if billing_type = fixed_cost then payment_status can be not_invoiced or paid

if actual_delivery_at not null start_at cannot be null

if actual_delivery_at not null and actual_delivery_at < now() then  progress = completed(80% chance), cancelled(20% chance)
if actual_delivery_at not null and actual_delivery_at > now() then  progress can be in_progress, blocked, cancelled
if actual_delivery_at = null and start_at = null then progress can be in_progress,
if actual_delivery_at = null and start_at not  null then progress cannot be not_started
*/
