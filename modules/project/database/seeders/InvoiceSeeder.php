<?php
namespace Modules\Project\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Modules\Project\Models\Project;
use Modules\Project\Models\Invoice;
use Faker\Factory as FakerFactory;
use Faker\Generator as Faker;
use App\Traits\GeneratesPlaceholderImages;
use Illuminate\Support\Facades\File;


class InvoiceSeeder extends Seeder
{
    use GeneratesPlaceholderImages;

    /**
     * Faker instance
     */
    protected Faker $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * Run the database seeds.
     *
     * NOTE: Projects already exist in the database, we only create invoices for them.
     */
    public function run(): void
    {

        // create users folder          
        $userfolderPath     = storage_path('app/public/invoices');                       

        if (!File::exists($userfolderPath)) {
            File::makeDirectory($userfolderPath, 0777, true);
            $this->command->alert($userfolderPath.' - Folder created successfully.');
        }

        Project::all()->each(function (Project $project) {
            $this->seedInvoicesForProject($project);
        });
    }

    /**
     * Create the income & cost invoices for a single project.
     */
    private function seedInvoicesForProject(Project $project): void
    {
        // Rule: "not_invoiced" projects never get invoices.
        if ($project->payment_status === 'not_invoiced') {
            return;
        }

        // We need a date to anchor the invoice dates around. Fall back to "now"
        // if the project somehow has no start_at.
        $startAt = $project->start_at ? Carbon::parse($project->start_at) : now();

        // Rule: for each project, create < 5 income invoices and < 5 cost invoices (1 to 4 each).
        $incomeCount = rand(1, 4);
        $costCount = rand(1, 4);

        // Build all invoice dates (income + cost together), the earliest one
        // is placed relative to the project's start_at (see generateInvoiceDates()).
        $allDates = $this->generateInvoiceDates($startAt, $incomeCount + $costCount);

        // Shuffle so the earliest date doesn't always land on the same invoice type.
        shuffle($allDates);

        $incomeDates = array_slice($allDates, 0, $incomeCount);
        $costDates = array_slice($allDates, $incomeCount, $costCount);

        // Work out how much the income invoices vs cost invoices should sum up to,
        // based on the project's payment_status + estimated_cost / revenue.
        [$totalIncome, $totalCost] = $this->calculateTotals($project, $costCount);

        // Split each total across its invoices, with some random variation per invoice.
        $incomeAmounts = $this->splitAmount($totalIncome, $incomeCount);
        $costAmounts = $this->splitAmount($totalCost, $costCount);

        foreach ($incomeDates as $i => $date) {
            $this->createInvoice($project, 'income', $date, $incomeAmounts[$i]);
        }

        foreach ($costDates as $i => $date) {
            $this->createInvoice($project, 'cost', $date, $costAmounts[$i]);
        }
    }

    /**
     * Generate the invoice dates for a project.
     *
     * The earliest ("first") invoice date is picked relative to the project's start_at:
     *  - 20% chance: start_at is BEFORE the first invoice date (invoice happens after project starts)
     *  - 5%  chance: start_at is EQUAL to the first invoice date
     *  - 75% chance: first invoice date is BEFORE start_at (invoiced before the project officially starts)
     *
     * All the remaining invoice dates are scattered after that first date.
     */
    private function generateInvoiceDates(Carbon $startAt, int $totalCount): array
    {
        $roll = rand(1, 100);

        if ($roll <= 20) {
            // start_at < first invoice date
            $firstDate = (clone $startAt)->addDays(rand(1, 60));
        } elseif ($roll <= 25) {
            // start_at == first invoice date
            $firstDate = clone $startAt;
        } else {
            // first invoice date < start_at
            $firstDate = (clone $startAt)->subDays(rand(1, 60));
        }

        $dates = [$firstDate];

        for ($i = 1; $i < $totalCount; $i++) {
            $dates[] = (clone $firstDate)->addDays(rand(1, 90));
        }

        return $dates;
    }

    /**
     * Decide the target sum(income) and sum(cost) for a project, based on its
     * payment_status and estimated_cost / revenue values.
     *
     * Returns [$totalIncome, $totalCost].
     */
    private function calculateTotals(Project $project, int $costCount): array
    {
        $estimatedCost = $project->estimated_cost;
        $revenue = $project->revenue;

        // Used as a scale reference when there's no estimated_cost/revenue to anchor to.
        $fallbackBase = rand(1000, 50000);

        // $net = project's net cash position from its invoices (income - cost).
        if ($project->payment_status === 'partially_paid') {

            if ($estimatedCost) {
                if (rand(1, 100) <= 50) {
                    // Rule: sum(income) - sum(cost) < estimated_cost
                    $net = $estimatedCost * (rand(10, 90) / 100);
                } else {
                    // Rule: estimated_cost < sum(income) - sum(cost) < revenue
                    // (if revenue isn't set, just go a bit above estimated_cost)
                    $upperBound = $revenue ? $revenue : ($estimatedCost * 1.5);
                    $net = rand((int) $estimatedCost, (int) max($estimatedCost + 1, $upperBound));
                }
            } else {
                // Rule: no estimated_cost -> net can be any value
                $net = rand(-$fallbackBase, $fallbackBase);
            }
        } else {

            // payment_status === 'paid'
            if ($revenue) {
                if (rand(1, 100) <= 40) {
                    // Rule: sum(income) - sum(cost) < revenue
                    $net = $revenue * (rand(10, 90) / 100);
                } else {
                    // Rule: revenue < sum(income) - sum(cost)
                    $net = $revenue * (rand(110, 200) / 100);
                }
            } else {
                // Rule: no revenue -> net can be any value
                $net = rand(-$fallbackBase, $fallbackBase);
            }
        }

        // Decide the total cost amount for this project's cost invoices.
        $totalCost = 0;

        if ($costCount > 0) {
            // Make the cost total a random 10%-50% slice of $net's size,
            // plus a small $500 base so it's never trivially tiny.
            // This keeps cost roughly proportional to how big the project's numbers are.
            $totalCost = round(abs($net) * (rand(10, 50) / 100)) + 500;
        }

        // Since net = income - cost, we derive income by rearranging: income = net + cost. 
        // The max(0, ...) guards against a negative income total in case $net was very negative.
        $totalIncome = max(0, $net + $totalCost);

        return [round($totalIncome, 2), round($totalCost, 2)];
    }

    /**
     * Split a total amount across a number of invoices with random variation,
     * so amounts aren't perfectly equal. Returns amounts summing to $total.
     */
    private function splitAmount(float $total, int $count): array
    {
        if ($count === 0) {
            return [];
        }

        if ($total <= 0) {
            // Still give each invoice a small positive amount
            return array_fill(0, $count, round(rand(50, 500), 2));
        }

        // Random weights so the split isn't perfectly even
        $weights = [];
        for ($i = 0; $i < $count; $i++) {
            $weights[] = rand(1, 100);
        }
        $weightSum = array_sum($weights);

        $amounts = [];
        foreach ($weights as $weight) {
            $amounts[] = round(($weight / $weightSum) * $total, 2);
        }

        return $amounts;
    }

    /**
     * Create a single invoice record for the project.
     */
    private function createInvoice(Project $project, string $invoiceType, Carbon $invoiceDate, float $amount): void
    {
        // Pick a progress_level, weighted depending on how "paid" the project is overall.
        $progressLevel = $this->pickProgressLevel($project->payment_status);

        $dueDate = null;
        $costCategory = null;

        if ($invoiceType === 'cost') {
            // Rule: cost invoices have a cost_category
            $costCategory = $this->faker->randomElement([
                'employee_cost', 'infrastructure_cost', 'third_party_services', 'other',
            ]);

            // Rule: 90% chance invoice_date < due_date (add 5-30 days)
            //       10% chance due_date < invoice_date (subtract 5-30 days)
            if (rand(1, 100) <= 90) {
                $dueDate = (clone $invoiceDate)->addDays(rand(5, 30));
            } else {
                $dueDate = (clone $invoiceDate)->subDays(rand(5, 30));
            }
        }
        // Rule: income invoices -> cost_category = null, due_date = null (defaults above)

        // Rule: payment proof/reference only exist when the invoice is actually paid
        if ($progressLevel === 'paid') {
            $paymentMethod = $this->faker->randomElement(['bank_transfer', 'cash', 'cheque', 'online_payment']);
            
            //$proofOfPayment = 'proofs/' . $this->faker->uuid() . '.pdf';
            $proofOfPayment = $this->makePlaceholderImage('invoices', 630, 820, 'Invoice');

            $transactionReference = strtoupper($this->faker->bothify('TXN-########'));
        } else {
            $paymentMethod = 'pending';
            $proofOfPayment = null;
            $transactionReference = null;
        }

        Invoice::create([
            'name' => ucfirst($invoiceType) . ' Invoice - ' . $project->name,
            'description' => $this->faker->sentence(),
            'invoice_type' => $invoiceType,
            'cost_category' => $costCategory,
            'currency' => 'USD',
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'transaction_reference' => $transactionReference,
            'invoice_date' => $invoiceDate->format('Y-m-d'),
            'due_date' => $dueDate ? $dueDate->format('Y-m-d') : null,
            'proof_of_payment' => $proofOfPayment,
            'progress_level' => $progressLevel,
            'project_id' => $project->id,
        ]);
    }

    /**
     * Randomly pick a progress_level, weighted by the project's overall payment_status.
     */
    private function pickProgressLevel(?string $projectPaymentStatus): string
    {
        $roll = rand(1, 100);

        if ($projectPaymentStatus === 'paid') {
            // Mostly paid invoices, small chance of the rest
            switch (true) {				
                case $roll <= 85: return 'paid';
                case $roll <= 92: return 'pending';
                case $roll <= 97: return 'overdue';
                default:          return 'cancelled';
            }
        }

        // partially_paid: a healthy mix of statuses
        switch (true) {
            case $roll <= 50: return 'paid';
            case $roll <= 80: return 'pending';
            case $roll <= 92: return 'overdue';
            default:          return 'cancelled';
        }
    }
}


















/**
 * ============================================================
 *  INVOICE SEEDING RULES
 * ============================================================
 *
 * 1. INVOICE COUNT PER PROJECT
 *    - Income invoices per project : less than 5 (0-4)
 *    - Cost invoices per project   : less than 5 (0-4)
 *
 * 2. FIELDS BY INVOICE TYPE
 *
 *    a) invoice_type = income
 *       - cost_category = null
 *       - due_date      = null
 *
 *    b) invoice_type = cost
 *       - cost_category = one of: ['employee_cost', 'infrastructure_cost', 'third_party_services', 'other']
 *       - due_date:
 *           - 90% chance -> due_date is AFTER invoice_date  (invoice_date + 5 to 30 days)
 *           - 10% chance -> due_date is BEFORE invoice_date (invoice_date - 5 to 30 days)
 *
 * 3. CURRENCY
 *    - Always 'USD' for every invoice
 *
 * 4. PAYMENT DETAILS BY progress_level
 *
 *    a) progress_level = paid
 *       - proof_of_payment      -> must have a value
 *       - transaction_reference -> only has a value when proof_of_payment has a value
 *       - payment_method        -> one of: ['bank_transfer', 'cash', 'cheque', 'online_payment']
 *
 *    b) progress_level = pending / overdue / cancelled
 *       - proof_of_payment      = null
 *       - transaction_reference = null
 *       - payment_method        = 'pending'
 *
 * 5. PROJECT start_at vs. FIRST INVOICE invoice_date
 *    (relationship between the project's start date and its earliest invoice)
 *
 *    - 20% chance -> project.start_at  <  first invoice_date   (invoiced after project starts)
 *    -  5% chance -> project.start_at  =  first invoice_date   (invoiced exactly on start date)
 *    - 80% chance -> project.start_at  <  first invoice_date   (invoiced before project starts)
 *
 * 6. RULES BY project.payment_status
 *    (payment_status can be: 'not_invoiced', 'partially_paid', 'paid')
 *
 *    a) payment_status = not_invoiced
 *       - Do NOT create any invoices for this project
 *
 *    b) payment_status = partially_paid
 *       - If project.estimated_cost IS set:
 *           - 50% chance -> (sum of income - sum of cost) < estimated_cost
 *           - 50% chance -> estimated_cost < (sum of income - sum of cost) < revenue
 *       - If project.estimated_cost is NOT set:(sum of income - sum of cost) can be any value
 *
 *    c) payment_status = paid
 *       - If project.revenue IS set:
 *           - 40% chance -> revenue > (sum of income - sum of cost)
 *           - 60% chance -> revenue < (sum of income - sum of cost)
 *       - If project.revenue is NOT set: (sum of income - sum of cost) can be any value
 * ============================================================
 */

//=====================================
/*

for a project create invoices that invoice_type=income count < 5 
for a project create invoices that invoice_type=cost count < 5  

when invoice_type = income 
    invoices.cost_category = null, invoices.due_date=null


when invoice_type = cost    
    invoices.cost_category = ['employee_cost', 'infrastructure_cost', 'third_party_services','other']
    invoices.due_date (invoice_date < due_date (90% chance)   for due date add 5 to 30 days from invoice date)
    invoices.due_date (due_date < invoice_date  (10% chance)  for due date minus 5 to 30 days from invoice date)



currency = usd



when progress_level = paid 
    proof_of_payment have value,  only then have transaction_reference has value,
    payment_method = bank_transfer/cash/cheque/online_payment

when progress_level = pending/overdue/cancelled            
    proof_of_payment=null, transaction_reference=null
    payment_method = pending





project.stat date < first invoice invoice_date in a project (20% cahance)
project.stat date = first invoice invoice_date in a project(5% cahance)
first invoice invoice_date in a project  < project.stat date (80% cahance)



project.payment_status  has these values not_invoiced', 'partially_paid', 'paid'
    if project.project payment_status = not_invoiced then no need to create invoices for that project

    if project.project payment_status = partially_paid then (if project.estimate_cost have value)
        [sum of income - sum of cost < estimate_cost (50% chance)]
        [estimate_cost  <  sum of income - sum of cost < revenue (50% chance)]
    if project.estimate_cost not have value (sum of income - sum of cost) can have any value 


    if project.project payment_status = paid then (if project.revenue have value)
        [revenue > sum of income - sum of cost (40% chance)]
        [revenue < sum of income - sum of cost (60% chance)]
    if project.revenue not have value (sum of income - sum of cost) can have any value 


*/
