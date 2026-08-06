<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use App\Models\User;
use Modules\Timesheet\Models\Timesheet;
use Modules\Timesheet\Models\TimesheetEntry;
use Modules\Task\Models\DeveloperTaskAssignment;
use Modules\Leave\Models\Leave;
use App\Models\Role;

class TimesheetSeeder extends Seeder
{

    /**
     * Role slugs that may post a thread / message on ANY task,
     * with no enrollment requirement.
     */
    private const TIMESHEET_REVIEW_ROLE_SLUGS = [
        Role::ADMIN,
        Role::OWNER,
        Role::MANAGER,
    ];

    /**
     * Office day window used to cap hours on leave days.
     * 8:00 AM - 6:00 PM = 10 hours = 600 minutes.
     */
    const OFFICE_MINUTES = 600;

    /**
     * Normal full work day target (in minutes).
     */
    const FULL_DAY_MINUTES = 480; // 8 hours

    /**
     * Absolute max minutes allowed on a normal (non-leave) day.
     */
    const MAX_DAY_MINUTES = 540; // 9 hours

    public function run(): void
    {
        // NOTE: adjust this if your app does not use spatie/laravel-permission.
        // e.g. replace with: User::where('role', 'developer')->get();
        $developers = User::whereHas('roles', function ($q) {
                $q->where('slug', Role::DEVELOPER);
            })
            ->where('employment_status', 'active')
            ->get();


        $reviewers = User::whereHas('roles', function ($q) {
                $q->whereIn('slug', self::TIMESHEET_REVIEW_ROLE_SLUGS);
            })
            ->where('employment_status', 'active')
            ->get();  

        if ($developers->isEmpty()) {
            $this->command->warn('No developers found. Skipping TimesheetSeeder.');
            return;
        }

        foreach ($developers as $developer) {

            // Every developer gets a handful of past weekly timesheets.
            //$numberOfWeeks = rand(4, 10);

            // Start counting back from the most recent Monday.
            //$firstWeekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->subWeeks($numberOfWeeks);

            // All assignments belonging to this developer (through the enrollment).
            $assignments = DeveloperTaskAssignment::whereHas('developerProjectEnrollment', function ($q) use ($developer) {
                    $q->where('developer_id', $developer->id);
                })
                ->where('progress', '!=', 'not_started') // not_started can never generate entries
                ->get();

            if ($assignments->isEmpty()) {
                continue; // nothing to generate timesheets for
            }

            // Start from the Monday of the week the developer's earliest assignment began.
            $earliestAssignedDate = $assignments->min('assigned_date_time');
            $firstWeekStart = Carbon::parse($earliestAssignedDate)->startOfWeek(Carbon::MONDAY);

            // Generate one weekly timesheet per week from that week up to the current week.
            $numberOfWeeks = $firstWeekStart->diffInWeeks(Carbon::now()->startOfWeek(Carbon::MONDAY)) + 1;
            
            for ($w = 0; $w < $numberOfWeeks; $w++) {

                $weekStartDate = $firstWeekStart->copy()->addWeeks($w); // Monday
                $weekEndDate   = $weekStartDate->copy()->addDays(4);    // Friday

                // Don't create timesheets for weeks that haven't happened yet.
                if ($weekEndDate->isFuture()) {
                    continue;
                }

                $timesheet = $this->createTimesheet($developer, $reviewers, $weekStartDate, $weekEndDate);

                $this->createEntriesForWeek($timesheet, $developer, $assignments, $weekStartDate, $weekEndDate);
            }
        }
    }

    /**
     * Create a single weekly timesheet record with realistic dates/status.
     */
    private function createTimesheet(User $developer, $reviewers, Carbon $weekStart, Carbon $weekEnd): Timesheet
    {
        $progress = $this->pickTimesheetProgress();

        // created_at must be strictly after week_end_date + 1 day, and well before + 1 month.
        $createdAt = $weekEnd->copy()->addDays(rand(1, 27))->addHours(rand(1, 23));

        $submittedAt = null;
        $reviewedAt  = null;
        $reviewedBy  = null;

        // submitted_by is always set to the developer, regardless of progress
        // (pending, approved, rejected, and draft all require submitted_by != null).
        $submittedBy = $developer->id;

        // Only approved/rejected timesheets have actually been submitted for review.
        // pending and draft both keep submitted_at = null.
        if (in_array($progress, ['approved', 'rejected', 'pending'])) {
            $submittedAt = $createdAt->copy()->addHours(rand(1, (24 * 7) - 1)); // within 1 week of creation
        }

        // Draft timesheets have never been submitted.
        if ($progress == 'pending') {
            $reviewer   = null;
            $reviewedAt = null;
            $reviewedBy = null;
        }

        // Only approved/rejected timesheets have been reviewed.
        if (in_array($progress, ['approved', 'rejected']) && $reviewers->isNotEmpty()) {
            $reviewer   = $reviewers->random();
            $reviewedAt = $submittedAt->copy()->addDays(rand(3, 14));
            $reviewedBy = $reviewer->id;
        }

        $timesheet = new Timesheet([
            'timesheet_type'  => 'regular',
            'week_start_date' => $weekStart->format('Y-m-d'),
            'week_end_date'   => $weekEnd->format('Y-m-d'),
            'progress'        => $progress,
            'submitted_at'    => $submittedAt,
            'submitted_by'    => $submittedBy,
            'reviewed_at'     => $reviewedAt,
            'reviewed_by'     => $reviewedBy,
        ]);

        // created_at/updated_at aren't mass-assignable, so set them directly.
        $timesheet->created_at = $createdAt;
        $timesheet->updated_at = $createdAt;
        $timesheet->save();

        return $timesheet;
    }

    /**
     * Weighted random status for a timesheet.
     * Mostly approved, with some pending/rejected/draft mixed in.
     */
    private function pickTimesheetProgress(): string
    {
        $roll = rand(1, 100);

        if ($roll <= 50) return 'approved';
        if ($roll <= 70) return 'pending';
        if ($roll <= 85) return 'rejected';
        return 'draft';
    }

    /**
     * Walk every weekday in the timesheet's week and create entries for it.
     *
     * pending / approved / rejected timesheets must end up with at least one
     * timesheet_entries row; draft timesheets are allowed to have zero.
     */
    private function createEntriesForWeek(Timesheet $timesheet, User $developer, $assignments, Carbon $weekStart, Carbon $weekEnd): void
    {
        $entriesCreated = 0;

        for ($date = $weekStart->copy(); $date->lte($weekEnd); $date->addDay()) {

            // Skip days that are in the future (can't have logged time yet).
            if ($date->isFuture()) {
                continue;
            }

            $leave = $this->findApprovedLeaveForDate($developer->id, $date);

            // Full day leave = no timesheet entries at all for this date.
            if ($leave && $leave->time_period === 'full_day') {
                continue;
            }

            $availableMinutes = $this->getAvailableMinutes($leave);
            if ($availableMinutes <= 0) {
                continue;
            }

            // How many minutes to actually log today.
            // On leave days: anywhere up to the reduced available time.
            // On normal days: anywhere up to 9h, biased toward a full 8h day.
            $maxMinutes = $leave ? $availableMinutes : self::MAX_DAY_MINUTES;
            $minMinutes = $leave ? (int) ($availableMinutes * 0.4) : (int) (self::FULL_DAY_MINUTES * 0.8);
            $targetMinutes = rand(max(15, $minMinutes), max(15, $maxMinutes));

            // Which of the developer's assignments were active on this date?
            $activeAssignments = $this->getActiveAssignments($assignments, $date, $weekEnd);

            if ($activeAssignments->isEmpty()) {
                continue; // nothing to log this day
            }

            $entriesCreated += $this->createDayEntries($timesheet, $activeAssignments, $date, $targetMinutes);
        }

        // pending / approved / rejected timesheets are required to have at least one
        // entry. If the day-by-day pass above produced none (e.g. every day this week
        // was full-day leave, or no assignment was active), force a single minimal
        // entry on the first day that actually had an active assignment - ignoring the
        // normal leave-driven skip logic, since this is a fallback of last resort.
        if ($entriesCreated === 0 && $timesheet->progress !== 'draft') {
            $forced = $this->forceMinimumEntry($timesheet, $assignments, $weekStart, $weekEnd);

            if (!$forced) {
                // No assignment was active at any point this week, so there's literally
                // no task_assignment_id we could attach an entry to. A pending/approved/
                // rejected timesheet can't legally exist without at least one entry, so
                // fall back to draft to keep the record consistent with that rule.
                $timesheet->progress     = 'draft';
                $timesheet->submitted_at = null;
                //$timesheet->submitted_by = $developer->id;
                $timesheet->reviewed_at  = null;
                $timesheet->reviewed_by  = null;
                $timesheet->save();
            }
        }
    }

    /**
     * Filter a developer's assignments down to the ones active on the given date,
     * based on each assignment's progress-driven date window.
     */
    private function getActiveAssignments($assignments, Carbon $date, Carbon $weekEnd)
    {
        return $assignments->filter(function ($assignment) use ($date, $weekEnd) {
            [$rangeStart, $rangeEnd] = $this->getAssignmentDateRange($assignment, $weekEnd);

            if (!$rangeStart || !$rangeEnd) {
                return false;
            }

            // Carbon::between() silently swaps arguments if start > end, causing false
            // positives for weeks before the assignment existed. Explicitly check that
            // the assignment's active period overlaps with this week.
            if ($rangeStart->greaterThan($rangeEnd)) {
                return false; // Assignment wasn't active during this period
            }

            return $date->between($rangeStart, $rangeEnd);
        })->values();
    }

    /**
     * Force at least one timesheet_entries row to exist for this timesheet by
     * scanning the week for the first day that had an active assignment, ignoring
     * the normal leave-driven skip logic. Returns true if an entry was created,
     * false if no assignment was active at any point during the week.
     */
    private function forceMinimumEntry(Timesheet $timesheet, $assignments, Carbon $weekStart, Carbon $weekEnd): bool
    {
        for ($date = $weekStart->copy(); $date->lte($weekEnd); $date->addDay()) {
            if ($date->isFuture()) {
                continue;
            }

            $activeAssignments = $this->getActiveAssignments($assignments, $date, $weekEnd);

            if ($activeAssignments->isEmpty()) {
                continue;
            }

            $minutes = rand(15, self::FULL_DAY_MINUTES);
            $this->createEntry($timesheet, $activeAssignments->first(), $date, $minutes);

            return true;
        }

        return false;
    }

    /**
     * Find an approved leave (full or partial) covering the given date.
     */
    private function findApprovedLeaveForDate(int $developerId, Carbon $date): ?Leave
    {
        return Leave::forUser($developerId)
            ->approved()
            ->whereDate('start_date', '<=', $date->format('Y-m-d'))
            ->whereDate('end_date', '>=', $date->format('Y-m-d'))
            ->first();
    }

    /**
     * Available working minutes for the day, reduced if there's a partial leave.
     */
    private function getAvailableMinutes(?Leave $leave): int
    {
        if (!$leave) {
            return self::OFFICE_MINUTES;
        }

        switch ($leave->time_period) {
            case 'half_day_morning':
            case 'half_day_afternoon':
                return (int) (self::OFFICE_MINUTES / 2);

            case 'short_leave':
            case 'custom_time':
                if ($leave->from_time && $leave->to_time) {
                    $leaveMinutes = Carbon::parse($leave->from_time)->diffInMinutes(Carbon::parse($leave->to_time));
                    return max(0, self::OFFICE_MINUTES - $leaveMinutes);
                }
                // No specific times set, assume a 1 hour leave as a fallback.
                return self::OFFICE_MINUTES - 60;

            default:
                return self::OFFICE_MINUTES;
        }
    }

    /**
     * Work out the [start, end] date window an assignment can generate
     * timesheet entries for, based on its progress status.
     */
    private function getAssignmentDateRange(DeveloperTaskAssignment $assignment, Carbon $weekEnd): array
    {
        if (!$assignment->assigned_date_time) {
            return [null, null];
        }

        $start = Carbon::parse($assignment->assigned_date_time);
        $stopped = $assignment->stopped_date_time ? Carbon::parse($assignment->stopped_date_time) : null;

        switch ($assignment->progress) {

            // Still going, or finished normally: usable up to this week's end
            // (never later than "now", since the future hasn't happened yet).
            case 'in_progress':
            case 'completed':
                $end = $weekEnd->copy()->min(Carbon::now());
                break;

            // Work stopped part way through: usable up to the stop date.
            case 'blocked':
            case 'cancelled':
                $end = $stopped;
                break;

            // Mixed: use the stop date if one was recorded, otherwise
            // treat it like an in-progress assignment.
            case 'mixed':
                $end = $stopped ?? $weekEnd->copy()->min(Carbon::now());
                break;

            default:
                $end = null;
        }

        return [$start, $end];
    }

    /**
     * Split the day's target minutes across the assignments that were
     * active that day, and create one timesheet_entries row per assignment.
     *
     * @return int number of entries actually created
     */
    private function createDayEntries(Timesheet $timesheet, $assignments, Carbon $date, int $totalMinutes): int
    {
        $count = $assignments->count();
        $remaining = $totalMinutes;
        $created = 0;

        foreach ($assignments as $index => $assignment) {

            $isLast = $index === $count - 1;

            if ($isLast) {
                $minutes = $remaining;
            } else {
                $share = (int) ($remaining / ($count - $index));
                $minutes = rand(max(15, (int) ($share * 0.5)), max(15, $share));
                $minutes = min($minutes, $remaining);
            }

            $remaining -= $minutes;

            if ($minutes <= 0) {
                continue;
            }

            $this->createEntry($timesheet, $assignment, $date, $minutes);
            $created++;
        }

        return $created;
    }

    /**
     * Create a single timesheet_entries row.
     */
    private function createEntry(Timesheet $timesheet, DeveloperTaskAssignment $assignment, Carbon $date, int $minutes): void
    {
        // Reviewer comments only make sense once a timesheet has been reviewed.
        // Rejected entries are a bit more likely to carry a comment than approved ones.
        $reviewerComment = null;
        if ($timesheet->progress === 'rejected' && rand(1, 100) <= 70) {
            $reviewerComment = 'Please double check the logged hours for this task.';
        } elseif ($timesheet->progress === 'approved' && rand(1, 100) <= 30) {
            $reviewerComment = 'Looks good.';
        }

        $entry = new TimesheetEntry([
            'task_assignment_id' => $assignment->id,
            'date'               => $date->format('Y-m-d'),
            'spend_time'         => $minutes,
            'comment'            => rand(1, 100) <= 40 ? 'Worked on assigned task.' : null,
            'reviewer_comment'   => $reviewerComment,
            'timesheet_id'       => $timesheet->id,
        ]);

        // Entries share the same created/updated timestamp as their timesheet.
        $entry->created_at = $timesheet->created_at;
        $entry->updated_at = $timesheet->created_at;
        $entry->save();
    }
}


/**
 * =========================================================================
 * TIMESHEET SEEDING RULES
 * =========================================================================
 *
 * -------------------------------------------------------------------------
 * 1. TIMESHEETS TABLE
 * -------------------------------------------------------------------------
 *
 * Basic fields
 * - timesheet_type       : always "regular"
 * - week_start_date      : must be a Monday
 * - week_end_date        : must be a Friday
 * - submitted_by         : must be a user with the "developer" role; always set,
 *                          regardless of progress (pending/approved/rejected/draft)
 * - submitted_at         : set only when progress is approved, pending, rejected;
 *                          null for draft
 * - reviewed_by          : must be a user with role "manager", "owner", or "admin"
 *
 * Minimum entries per timesheet
 * - pending / approved / rejected : at least one timesheet_entries row required
 * - draft                         : zero timesheet_entries rows is allowed
 *
 * Which task assignments can generate entries
 * - not_started : NOT usable — no entries can be created
 * - in_progress : usable, from assigned_date_time  -> timesheet.week_end_date
 * - completed   : usable, from assigned_date_time  -> timesheet.week_end_date
 * - blocked     : usable, from assigned_date_time  -> assignment.stopped_date_time
 * - cancelled   : usable, from assigned_date_time  -> assignment.stopped_date_time
 * - mixed       : usable, range depends on stopped_date_time:
 *                   - if stopped_date_time IS set   -> assigned_date_time -> stopped_date_time
 *                   - if stopped_date_time NOT set  -> assigned_date_time -> timesheet.week_end_date
 *
 * Date ordering rules
 * - week_end_date + 1 day   <  created_at        <  week_end_date + 1 month
 * - created_at + 1 hour     <  submitted_at      <  created_at + 1 week
 * - submitted_at + 3 days   <  reviewed_at       <  submitted_at + 2 weeks
 *
 * Entry count requirement — depends on parent timesheet's progress
 * - pending  : must have AT LEAST 1 timesheet_entry
 * - approved : must have AT LEAST 1 timesheet_entry
 * - rejected : must have AT LEAST 1 timesheet_entry
 * - draft    : may have ZERO timesheet_entries (it's fine to have none)
 *
 * Reviewer comments — depends on parent timesheet's progress
 * - pending  : entries must NOT have a reviewer_comment
 * - draft    : entries must NOT have a reviewer_comment
 * - approved : entries may OR may not have a reviewer_comment (mixed)
 * - rejected : some entries SHOULD have a reviewer_comment
 *
 * -------------------------------------------------------------------------
 * 2. TIMESHEET_ENTRIES TABLE
 * -------------------------------------------------------------------------
 *
 * Date range
 * - Every entry date must fall within the parent timesheet's week:
 *     week_start_date <= entry.date <= week_end_date
 *
 * Daily hours — no leave that day
 * - Sum of spend_time for the day should target ~8 hours
 * - Less than 8 hours total is acceptable
 * - 9 hours is the maximum allowed
 *
 * Daily hours — user has leave that day
 * - Full-day leave   : create NO entries for that date
 * - Partial leave    : sum of spend_time must not exceed the user's remaining available time for the day
 *                       (available time = office hours 8:00 AM - 6:00 PM,minus the leave period)
 * - Being under the available time is acceptable
 *
 * Reviewer comments — depends on parent timesheet's progress
 * - pending  : entries must NOT have a reviewer_comment
 * - draft    : entries must NOT have a reviewer_comment
 * - approved : entries may OR may not have a reviewer_comment (mixed)
 * - rejected : some entries SHOULD have a reviewer_comment
 *
 * Timestamps
 * - entry.created_at must equal the parent timesheet's created_at
 */



/*
==conditions relate to timesheets table==

    timesheets.submitted_by = users that have role of developer

    when choose task assignments from developer_task_assignments table
        if developer_task_assignments.progress == not_started
        then this task assignment can not use to create timesheet entries

        if developer_task_assignments.progress == in_progress  
        then this task assignment can use to create timesheet entries
        this task assignment can use to create timesheet entries from task_assignment.assigned_date_time to timesheet.week_end_date
     
        if developer_task_assignments.progress == completed  
        then this task assignment can use to create timesheet entries  
        this task assignment can use to create timesheet entries from task_assignment.assigned_date_time to timesheet.week_end_date

        if developer_task_assignments.progress == blocked  
        then this task assignment can use to create timesheet entries
        this task assignment can use to create timesheet entries from task_assignment.assigned_date_time to timesheet.stopped_date_time

        if developer_task_assignments.progress == cancelled 
        then this task assignment can use to create timesheet entries
        this task assignment can use to create timesheet entries from task_assignment.assigned_date_time to timesheet.stopped_date_time

        if developer_task_assignments.progress == mixed   
        then this task assignment can use to create timesheet entries
        this task assignment can use to create timesheet entries shown below
            if set stopped_date_time 
                create timesheet entries from task_assignment.assigned_date_time to timesheet.stopped_date_time
            else 
                create timesheet entries from task_assignment.assigned_date_time to timesheet.week_end_date     

    timesheets.timesheet_type = regular

    timesheets.week_start_date should be monday

    timesheets.week_end_date should be friday

    timesheets.week_end_date + 1 days < timesheets.created_at < timesheets.week_end_date + month

    create_at+1 hour < submitted_at < create_at + 1 week

    reviewed_by = users that have these roles manager / owner / admin

    submitted_at+3 days < reviewed_at < submitted_at +2 weeks


    when timesheets.progress =pending  timesheets.submitted_at = null  timesheets.submitted_by != null 
    when timesheets.progress =approved  timesheets.submitted_at != null  timesheets.submitted_by != null 
    when timesheets.progress =rejected  timesheets.submitted_at != null  timesheets.submitted_by != null 
    when timesheets.progress =draft  timesheets.submitted_at = null  timesheets.submitted_by != null 

    for pending, approved,rejected  progress states at least 1 timesheet_entry should exist

    for draft progress state there can happen 0 timesheet_entry




==conditions relate to timesheet_entries table==

    timesheets.week_start_date =< timesheet_entries.date  =<  timesheets.week_end_date   

    =if user not have full or partial leave= 
    timesheet_entries table each date for a developer should have time entries spend_time sum should be 8 hours
    in time entries  sum < 8 then it is ok
    in time entries  sum max = 9

    =if user have leave= 
    if full leave then no timesheet_entries for that day
    if user have full or partial leave timesheet_entries.spend_time for that date should sum upto total available time(office time - 8am to 6pm)
    in time entries  sum < user available time then it is ok

    if timesheets.progress =  pending then   timesheets -belong-> timesheet_entries no reviewer_comment  
    if timesheets.progress =  approved then  timesheets -belong-> timesheet_entries some of them have/not have  reviewer_comment
    if timesheets.progress =  rejected then  timesheets -belong-> timesheet_entries some of them have reviewer_comment
    if timesheets.progress =  draft then     timesheets -belong-> timesheet_entries no reviewer_comment

    timesheets.created_at  = timesheet_entries.created_at
*/
