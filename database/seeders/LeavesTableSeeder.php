<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Modules\Leave\Models\Leave as LeaveModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeavesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $baseDate = Carbon::parse('2026-02-01');
        //$baseDate = now();
        

        // Truncate the table first
        //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //LeaveModel::truncate();
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get users by role
        $appliers = User::whereHas('roles', function ($query) {
            $query->whereIn('slug', ['project_manager', 'developer']);
        })->pluck('id')->toArray();

        $responders = User::whereHas('roles', function ($query) {
            $query->whereIn('slug', ['admin', 'owner', 'manager']);
        })->pluck('id')->toArray();

        // If no users found, exit
        if (empty($appliers)) {
            $this->command->warn('No users found with roles: project_manager, developer');
            return;
        }

        if (empty($responders)) {
            $this->command->warn('No users found with roles: admin, owner, manager');
            return;
        }

        // Sample data
        $leaves = [];

        // 1. Full day leave (approved)
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            $responders[array_rand($responders)],
            $baseDate->copy()->addDays(10)->toDateString(),
            $baseDate->copy()->addDays(12)->toDateString(),
            'annual',
            'approved',
            'full_day',
            null,
            null,
            'Annual vacation to visit family',
            $baseDate->copy()->subDays(5),
            $baseDate->copy()->subDays(3),
            'Approved - Enjoy your vacation!'
        );

        // 2. Half day morning (pending)
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            null,
            $baseDate->copy()->addDays(5)->toDateString(),
            null,
            'casual',
            'pending',
            'half_day_morning',
            '08:00:00',
            '12:20:00',
            'Doctor appointment in the morning',
            $baseDate->copy()->subDays(2),
            null,
            null
        );

        // 3. Half day afternoon (rejected)
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            $responders[array_rand($responders)],
            $baseDate->copy()->addDays(3)->toDateString(),
            null,
            'medical',
            'rejected',
            'half_day_afternoon',
            '12:30:00',
            '17:30:00',
            'Medical checkup',
            $baseDate->copy()->subDays(7),
            $baseDate->copy()->subDays(5),
            'Rejected - Please provide medical certificate'
        );


        // 4. Short leave (approved)
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            $responders[array_rand($responders)],
            $baseDate->copy()->addDays(7)->toDateString(),
            null,
            'casual',
            'approved',
            'short_leave',
            '10:00:00',
            '12:00:00',
            'Bank appointment',
            $baseDate->copy()->subDays(3),
            $baseDate->copy()->subDays(1),
            'Approved'
        );



        // 5. Custom time (pending)
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            null,
            $baseDate->copy()->addDays(8)->toDateString(),
            null,
            'other',
            'pending',
            'custom_time',
            '09:30:00',
            '13:30:00',
            'Personal work',
            $baseDate->copy()->subDays(1),
            null,
            null
        );


        // 6. Multiple days leave (approved)
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            $responders[array_rand($responders)],
            $baseDate->copy()->addDays(15)->toDateString(),
            $baseDate->copy()->addDays(19)->toDateString(),
            'annual',
            'approved',
            'full_day',
            null,
            null,
            'Family vacation',
            $baseDate->copy()->subDays(10),
            $baseDate->copy()->subDays(8),
            'Approved - Have a great trip!'
        );

        // 7. Cancelled leave
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            null,
            $baseDate->copy()->addDays(20)->toDateString(),
            null,
            'casual',
            'cancelled',
            'full_day',
            null,
            null,
            'Personal day off - Cancelled',
            $baseDate->copy()->subDays(3),
            null,
            null
        );


        // 8. Short leave with different times (pending)
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            null,
            $baseDate->copy()->addDays(12)->toDateString(),
            null,
            'medical',
            'pending',
            'short_leave',
            '14:00:00',
            '16:00:00',
            'Dental checkup',
            $baseDate->copy()->subDays(1),
            null,
            null
        );

        // 9. Custom time with different hours (approved)
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            $responders[array_rand($responders)],
            $baseDate->copy()->addDays(25)->toDateString(),
            null,
            'other',
            'approved',
            'custom_time',
            '11:00:00',
            '15:00:00',
            'Home inspection',
            $baseDate->copy()->subDays(5),
            $baseDate->copy()->subDays(3),
            'Approved - 4 hours leave'
        );

        // 10. Full day leave for next month (pending)
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            null,
            $baseDate->copy()->addMonth()->startOfMonth()->addDays(5)->toDateString(),
            $baseDate->copy()->addMonth()->startOfMonth()->addDays(6)->toDateString(),
            'annual',
            'pending',
            'full_day',
            null,
            null,
            'Long weekend trip planning',
            $baseDate->copy()->subDays(2),
            null,
            null
        );

        // 11. Full day medical leave (approved) - starts tomorrow
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            $responders[array_rand($responders)],
            $baseDate->copy()->addDay()->toDateString(),
            null,
            'medical',
            'approved',
            'full_day',
            null,
            null,
            'Sick leave - fever and cold',
            $baseDate->copy()->subDay(),
            $baseDate->copy()->subHours(2),
            'Approved - Get well soon!'
        );


        // 12. Casual leave half day morning (pending) - next week
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            null,
            $baseDate->copy()->addDays(14)->toDateString(),
            null,
            'casual',
            'pending',
            'half_day_morning',
            '08:00:00',
            '12:20:00',
            'Personal errands',
            $baseDate->copy()->subHours(3),
            null,
            null
        );

        
        // 13. Annual leave custom time (approved) - next month
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            $responders[array_rand($responders)],
            $baseDate->copy()->addDays(30)->toDateString(),
            $baseDate->copy()->addDays(32)->toDateString(),
            'annual',
            'approved',
            'custom_time',
            '09:00:00',
            '13:00:00',
            'Family event - need to leave early',
            $baseDate->copy()->subDays(2),
            $baseDate->copy()->subDay(),
            'Approved - 4 hours leave'
        );


        // 14. Short leave (rejected) - day after tomorrow
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            $responders[array_rand($responders)],
            $baseDate->copy()->addDays(2)->toDateString(),
            null,
            'other',
            'rejected',
            'short_leave',
            '15:00:00',
            '17:00:00',
            'Meeting with real estate agent',
            $baseDate->copy()->subDays(3),
            $baseDate->copy()->subDay(),
            'Rejected - Please reschedule as project deadline is near'
        );

        // 15. Full day casual leave (cancelled) - next week
        $leaves[] = $this->createLeaveData(
            $appliers[array_rand($appliers)],
            null,
            $baseDate->copy()->addDays(21)->toDateString(),
            $baseDate->copy()->addDays(22)->toDateString(),
            'casual',
            'cancelled',
            'full_day',
            null,
            null,
            'Weekend getaway - cancelled due to workload',
            $baseDate->copy()->subDays(7),
            null,
            null
        );

        // Insert all leaves
        foreach ($leaves as $leaveData) {
            $leaveRec = new LeaveModel($leaveData);   
            $leaveRec->forceFill([
                'created_at' => $leaveData['created_at'],
                'updated_at' => $leaveData['updated_at'],
            ])->save();
        }

        $this->command->info('Leaves seeded successfully: ' . count($leaves) . ' records created.');
        $this->command->newLine();
    }

    /**
     * Create leave data array
     */
    private function createLeaveData(
        $userId,
        $respondedBy,
        $startDate,
        $endDate,
        $leaveType,
        $progress,
        $timePeriod,
        $fromTime,
        $toTime,
        $reason,
        $appliedAt,
        $respondedAt,
        $responseMessage
    ) {
        // Validate time period rules
        if ($timePeriod === 'full_day') {
            $fromTime = null;
            $toTime = null;
        } elseif (in_array($timePeriod, ['half_day_morning', 'half_day_afternoon'])) {
            // These have predefined times
            // Already set correctly in the caller
        } elseif (in_array($timePeriod, ['short_leave', 'custom_time'])) {
            // Ensure from_time < to_time and difference is valid
            if ($fromTime && $toTime) {
                $from = Carbon::parse($fromTime);
                $to = Carbon::parse($toTime);
                $diffHours = $from->diffInHours($to);
                
                if ($diffHours > 2 && $timePeriod === 'short_leave') {
                    // Adjust to 2 hours max for short leave
                    $to = $from->copy()->addHours(2);
                    $toTime = $to->format('H:i:s');
                }
                
                if ($timePeriod === 'custom_time' && $diffHours >= 8) {
                    // Adjust to max 7.5 hours for custom time
                    $to = $from->copy()->addHours(7)->addMinutes(30);
                    $toTime = $to->format('H:i:s');
                }
            }
        }

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'leave_type' => $leaveType,
            'progress' => $progress,
            'time_period' => $timePeriod,
            'from_time' => $fromTime,
            'to_time' => $toTime,
            'applied_at' => $appliedAt,
            'applied_by' => $userId, // In real scenario, could be different user
            'reason' => $reason,
            'responded_at' => $respondedAt,
            'responded_by' => $respondedBy,
            'response_message' => $responseMessage,
            
            'created_at' => $appliedAt,
            'updated_at' => $appliedAt,
            'deleted_at' => null,
        ];
    }
}









/*
Schema::create('leaves', function (Blueprint $table) {
    // Primary identifier
    $table->id();
                
    $table->date('start_date');
    $table->date('end_date')->nullable();  // if leave has one day then this is null
    
    // Leave type and status
    $table->enum('leave_type', ['annual', 'casual', 'medical', 'other'])->default('annual');

    $table->enum('progress', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
    
    // Time period details
    $table->enum('time_period', [
        'full_day',
        'half_day_morning',
        'half_day_afternoon',
        'short_leave',
        'custom_time'
    ])->default('full_day');

    $table->time('from_time')->nullable();
    $table->time('to_time')->nullable();
                
    $table->timestamp('applied_at')->useCurrent();
    $table->foreignId('applied_by')->constrained('users');            
    $table->text('reason')->nullable();
    

    $table->timestamp('responded_at')->nullable();
    $table->foreignId('responded_by')->nullable()->constrained('users');
    $table->text('response_message')->nullable();


    // Timestamps
    $table->timestamps();
    
    // Soft delete for record keeping
    $table->softDeletes();
});




//things to check

//start_date < end_date and also end_date can be null


//applied_at < responded_at < start_date


//in here applied_at = create_at 



//applied_by = users that have role should be equal to project_manager,developer

//responded_by = users that have role should be equal to admin, owner,manager


// if time_period = full_day from_time = null, to_time = null
// if time_period = half_day_morning  from_time = 8am, to_time = 12.20 Pm
// if time_period = half_day_afternoon from_time = 12.30pm, to_time = 5.30 Pm
// if time_period = short_leave          from_time < to_time and difference between them is 2hours
// if time_period = custom_time from_time < to_time difference between them is x hours   x < 8 

*/