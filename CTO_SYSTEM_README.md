# CTO (Compensatory Time Off) System Enhancement

## Overview

This enhancement implements a comprehensive CTO management system with **1-year expiration** and **FIFO (First-In-First-Out) usage** for the Personal Information Management System.

## New Features

### 1. **1-Year Expiration Policy**
- All CTO earned expires exactly 1 year from the date it was approved
- Automatic expiration processing via scheduled commands
- Clear expiration warnings and tracking

### 2. **FIFO Usage System**
- When CTO is used, the oldest earned CTO is deducted first
- Ensures fair usage and prevents newer CTO from being used before older ones
- Automatic handling during leave request approval

### 3. **Individual CTO Entry Tracking**
- Each approved CTO request creates a separate CTO entry
- Tracks earned date, expiry date, and remaining days for each entry
- Detailed history and usage tracking

### 4. **Enhanced Dashboard**
- Detailed CTO balance information
- Expiration warnings and countdown
- Individual CTO entry details with dates
- Visual indicators for expiring CTOs

## Database Schema

### New Tables

#### `cto_entries`
- `id`: Primary key
- `school_head_id`: Reference to personnel
- `cto_request_id`: Reference to original CTO request
- `days_earned`: Days earned from this CTO request
- `days_remaining`: Days remaining (can be partial)
- `earned_date`: Date when CTO was earned (approved)
- `expiry_date`: 1 year from earned_date
- `is_expired`: Boolean flag for expired entries

#### `cto_usages`
- `id`: Primary key
- `school_head_id`: Reference to personnel
- `cto_entry_id`: Which CTO entry was used
- `leave_request_id`: Which leave request used it
- `days_used`: How many days were used from this CTO entry
- `used_date`: Date when CTO was used
- `usage_type`: Type of usage (leave, manual_adjustment)
- `notes`: Additional notes

## Key Components

### 1. **CTOService** (`app/Services/CTOService.php`)
Main service class that handles:
- Creating CTO entries when requests are approved
- FIFO usage when CTOs are consumed
- Balance calculations and updates
- Expiration management

### 2. **CTOEntry Model** (`app/Models/CTOEntry.php`)
Represents individual CTO entries with methods for:
- Checking expiration status
- Using CTO days with validation
- Querying available entries
- Calculating totals

### 3. **CTOUsage Model** (`app/Models/CTOUsage.php`)
Tracks CTO usage history with relationships to:
- CTO entries that were used
- Leave requests that triggered usage
- Usage details and notes

### 4. **Console Commands**

#### Expire Old CTOs
```bash
php artisan cto:expire-old          # Expire CTOs older than 1 year
php artisan cto:expire-old --dry-run # Preview what would be expired
```

#### CTO Management
```bash
php artisan cto:manage summary                    # System overview
php artisan cto:manage balance --school-head=123  # Check specific balance
php artisan cto:manage history --school-head=123  # Usage history
php artisan cto:manage expire                     # Manual expiration
```

## Usage Flow

### 1. **CTO Request Approval**
When a CTO request is approved:
1. A new `CTOEntry` is created with expiry date = approved_date + 1 year
2. The `SchoolHeadLeave` record is updated for backward compatibility
3. The CTO becomes available for use

### 2. **CTO Usage (Leave Request)**
When a school head takes "Compensatory Time Off" leave:
1. System finds available CTO entries ordered by `earned_date` (oldest first)
2. Deducts days from the oldest entries until the required days are met
3. Creates `CTOUsage` records for tracking
4. Updates remaining days in affected `CTOEntry` records

### 3. **Automatic Expiration**
Daily at 00:30 (configurable in `app/Console/Kernel.php`):
1. Command identifies CTO entries past their expiry date
2. Marks them as expired and sets `days_remaining` to 0
3. Updates school head leave balances
4. Logs expiration activity

## Dashboard Enhancements

The school head dashboard now shows:

### CTO Summary Card
- Total available CTO days
- Days earned vs. days used
- Expiration warnings

### CTO Details Section
- Individual CTO entries with dates
- Days remaining for each entry
- Expiration countdown
- Visual status indicators:
  - 🔴 **Red**: Expired
  - 🟡 **Yellow**: Expiring within 30 days  
  - 🟢 **Green**: More than 30 days remaining

### Policy Information
Clear explanation of:
- 1-year expiration policy
- FIFO usage system
- Automatic expiration process

## Migration and Backward Compatibility

### Data Migration
- Existing approved CTO requests are automatically migrated to the new system
- Each approved request creates a corresponding `CTOEntry`
- Expiry dates are calculated from the original approval date
- Existing `SchoolHeadLeave` records are updated to reflect current balances

### Backward Compatibility
- All existing functionality continues to work
- Legacy `SchoolHeadLeave` records are maintained for compatibility
- Dashboard views work with both old and new data structures

## Configuration

### Expiration Schedule
In `app/Console/Kernel.php`:
```php
$schedule->command('cto:expire-old')->daily()->at('00:30');
```

### Service Registration
The `CTOService` is automatically registered and can be dependency-injected into controllers.

## Testing

Run the test script to verify system functionality:
```bash
php test_cto_system.php
```

## Error Handling

The system includes comprehensive error handling for:
- Insufficient CTO balance validation
- Expired CTO usage prevention
- Failed database operations with rollback
- Invalid date calculations
- Missing personnel records

## Logging

All CTO operations are logged with details including:
- CTO entry creation and expiration
- Usage transactions with FIFO details
- Balance updates and calculations
- Error conditions and failures

## Security Considerations

- All CTO operations require proper authentication
- School heads can only manage their own CTOs
- Admin users can view but not arbitrarily modify CTO balances
- Database constraints prevent invalid data states

## Performance Optimization

- Database indexes on frequently queried fields
- Efficient FIFO queries using ordered selects
- Minimal database calls through service layer
- Cached balance calculations where appropriate

## Future Enhancements

Potential improvements for future versions:
- Email notifications for expiring CTOs
- Bulk CTO management for administrators
- CTO transfer between personnel
- Advanced reporting and analytics
- Mobile-responsive CTO management interface
