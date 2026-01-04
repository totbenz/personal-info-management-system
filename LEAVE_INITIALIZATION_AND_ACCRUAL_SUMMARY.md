# Leave Initialization and Accrual Summary

This document summarizes how leave records are created/initialized and how leave balances are updated ("auto increment") in the system.

## Scope

Covers:

- Admin account creation -> initializes leave rows to **zero** based on `personnel_id`.
- Monthly leave accrual for:
  - Teacher
  - Non-teaching
  - School Head
- Where/when the accrual runs (triggered by routes/pages, not a background timer).

---

# 1) Leave Tables (where balances are stored)

- `teacher_leaves`
  - Key columns: `teacher_id` (FK -> `personnels.id`), `leave_type`, `year`, `available`, `used`

- `non_teaching_leaves`
  - Key columns: `non_teaching_id` (FK -> `personnels.id`), `leave_type`, `year`, `available`, `used`

- `school_head_leaves`
  - Key columns: `school_head_id` (FK -> `personnels.id`), `leave_type`, `year`, `available`, `used`, `ctos_earned`

---

# 2) Admin creates an account -> initialize leaves to **0**

## What happens

Whenever an admin creates a user account, the system ensures the related leave rows exist for that personnel and sets balances to **0** (for the current year), based on the selected role.

- Teacher: creates rows in `teacher_leaves`
- Non-teaching: creates rows in `non_teaching_leaves`
- School head: creates rows in `school_head_leaves`
- Admin: no leave rows are created

All created rows start with:

- `available = 0`
- `used = 0`
- (school head only) `ctos_earned = 0`

Rows are created using `firstOrCreate(...)` to avoid duplicates.

## Where it is implemented

### Shared helper (single source of truth)

- `app/Models/Personnel.php`
  - Method: `initializeLeaveBalancesToZeroForRole(string $role, ?int $year = null): void`

### Account creation entry points

- Livewire admin modal:
  - `app/Livewire/AccountManagement.php` -> `create()`

- Admin accounts form route:
  - `app/Http/Controllers/UserController.php` -> `store()`

---

# 3) Monthly leave accrual (+1.25 per month)

## Important: how "automatic" works

Accrual does **not** run continuously in the background.

Instead, it runs when certain pages/routes are visited (controller code executes), which updates the database if needed.

This means balances can increase when the user opens their dashboard (or other pages wired to accrual).

---

# 4) Teacher monthly accrual

## Rule

- Applies to:
  - `Vacation Leave`
  - `Sick Leave`
- Accrual rate:
  - `+1.25` per eligible month

## Trigger (route/page)

- Runs when teacher opens dashboard:
  - `app/Http/Controllers/HomeController.php` -> `teacherDashboard()`

## Logic details

- Ensures records exist at **0** (for the current year):
  - `Personnel::initializeLeaveBalancesToZeroForRole('teacher', $year)`

- Then applies accrual:
  - `app(\App\Services\MonthlyLeaveAccrualService::class)->updateTeacherLeaveRecords($personnel->id, $year)`

## Safety behavior (does not overwrite deductions)

For each leave type:

- `currentTotal = available + used`
- `accruedTotal = eligibleMonths * 1.25`

If `currentTotal < accruedTotal`, the system adds only the difference to `available`:

- `difference = accruedTotal - currentTotal`
- `available = available + difference`

This preserves `used` and avoids resetting records.

---

# 5) Non-teaching monthly accrual

## Rule

- Applies to:
  - `Vacation Leave`
  - `Sick Leave`
- Accrual rate:
  - `+1.25` per eligible month

## Trigger (route/page)

- Runs when non-teaching opens dashboard:
  - `app/Http/Controllers/HomeController.php` -> `nonTeachingDashboard()`

## Logic details

- Ensures records exist at **0** (for the current year):
  - `Personnel::initializeLeaveBalancesToZeroForRole('non_teaching', $year)`

- Then applies accrual:
  - `app(\App\Services\MonthlyLeaveAccrualService::class)->updateNonTeachingLeaveRecords($personnel->id, $year)`

Same safety behavior as teacher: only tops up the missing difference.

---

# 6) School head leave accrual (existing system)

## Trigger (route/page)

School head accrual is also triggered by visiting pages (not background cron):

- School head dashboard:
  - `app/Http/Controllers/HomeController.php` -> `schoolHeadDashboard()`
  - Calls `SchoolHeadLeaveAccrualService->updateLeaveRecords(...)`

- School head monetization page:
  - `app/Http/Controllers/SchoolHeadMonetizationController.php`
  - Calls `SchoolHeadLeaveAccrualService->updateLeaveRecords(...)`

## Rule (different from teacher/non-teaching)

School head accrual currently calculates Vacation/Sick Leave with:

- Base amount: `15`
- Monthly accrual: `+1.25/month`
- Yearly bonus: `+15 per completed year`

Implemented in:

- `app/Services/SchoolHeadLeaveAccrualService.php`

It also uses a "top-up" approach for existing records:

- If total (`available + used`) is below calculated accrued total, it adds only the missing difference.

---

# 7) Manual ways leaves can increase

Even with accrual, there are routes that can manually add leave days:

- Teacher: `app/Http/Controllers/TeacherLeaveController.php` -> `addLeave()`
- Non-teaching: `app/Http/Controllers/NonTeachingLeaveController.php` -> `addLeave()`
- School head: `app/Http/Controllers/SchoolHeadLeaveController.php` -> `addLeave()`

Each one does:

- `available += days_to_add`

---

# 8) Scheduled adjustments (not accrual)

There is a scheduled yearly command that adjusts balances:

- Command: `leave:process-year-end-force-leave`
- File: `app/Console/Commands/ProcessYearEndForceLeave.php`
- Scheduled in: `app/Console/Kernel.php`

This is a year-end deduction/adjustment:

- Remaining `Force Leave` is deducted from `Vacation Leave`.

---

# Notes / Caveats

- Accrual requires `personnels.employment_start` to be set.
- Teacher/Non-teaching monthly accrual only affects Vacation/Sick. Other leave types remain as-is (often zero-initialized).
- Accrual currently updates when dashboards are opened. If you want true background accrual, it must be scheduled via Laravel Scheduler + cron.
