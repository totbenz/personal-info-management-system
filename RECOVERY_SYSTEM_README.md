# Database Recovery System

## Overview

The Database Recovery System provides a web-based interface for restoring your database from CSV export backups. This is particularly useful for disaster recovery scenarios when you need to quickly restore your database without command-line access.

## Access

- **URL**: `/recovery`
- **Authentication**: None required (publicly accessible)
- **Purpose**: Emergency database restoration

## Features

- **File Upload**: Drag-and-drop or click-to-upload ZIP files
- **Validation**: Ensures uploaded files are valid ZIP archives containing CSV files
- **Security**: Production environment protection (disabled by default)
- **Progress Feedback**: Real-time status updates during restoration
- **Error Handling**: Comprehensive error messages and logging

## How to Use

### Step 1: Prepare Your Backup
Ensure you have a valid database backup ZIP file created using:
```bash
php artisan db:csv export
```

### Step 2: Access Recovery Page
Navigate to `/recovery` in your browser.

### Step 3: Upload Backup File
- Click "Upload a file" or drag your ZIP file into the upload area
- Only ZIP files are accepted (max 100MB)
- The system will validate the file contains CSV files

### Step 4: Confirm Restoration
- Check the confirmation checkbox
- Click "Restore Database" button
- Confirm the action in the popup dialog

### Step 5: Wait for Completion
- The restoration process may take several minutes
- You'll see a loading indicator during the process
- Success/error messages will be displayed

## Security Considerations

### Production Environment
By default, the recovery system is **disabled in production** for security reasons. To enable it:

1. Add to your `.env` file:
   ```
   ALLOW_RECOVERY=true
   ```

2. Or add to your `config/app.php`:
   ```php
   'allow_recovery' => env('ALLOW_RECOVERY', false),
   ```

### File Validation
- Only ZIP files are accepted
- Files are validated to contain CSV data
- Maximum file size: 100MB
- Temporary files are automatically cleaned up

## Command Equivalent

The web interface executes the same command as:
```bash
php artisan db:csv import --file="path/to/export.zip"
```

## Logging

All recovery attempts are logged with:
- File information (name, size)
- Success/failure status
- Error details
- Timestamps

Check your Laravel logs for detailed information about recovery operations.

## Troubleshooting

### Common Issues

1. **"Database recovery is disabled in production"**
   - Enable recovery in production by setting `ALLOW_RECOVERY=true`

2. **"Invalid ZIP file"**
   - Ensure the file is a valid ZIP archive
   - Check if the file is corrupted

3. **"ZIP file does not contain any CSV files"**
   - Ensure you're using a backup created with `php artisan db:csv export`
   - Check that the ZIP contains CSV files

4. **"Database restoration failed"**
   - Check Laravel logs for detailed error information
   - Ensure database permissions are correct
   - Verify the backup file is complete

### File Size Limits
- Default PHP upload limit: 2MB
- Recovery system limit: 100MB
- Adjust `upload_max_filesize` and `post_max_size` in PHP if needed

## Best Practices

1. **Regular Backups**: Create backups regularly using the export command
2. **Test Restorations**: Periodically test your backup files
3. **Secure Access**: Consider IP restrictions for the recovery route
4. **Monitor Logs**: Check logs after restoration attempts
5. **Clean Environment**: Ensure the recovery route is disabled in production unless needed

## Technical Details

### Files Created
- `app/Http/Controllers/RecoveryController.php` - Main controller
- `resources/views/recovery/index.blade.php` - User interface
- Routes added to `routes/web.php`

### Dependencies
- Laravel's Artisan command system
- PHP ZipArchive extension
- Laravel's file storage system
- Tailwind CSS for styling

### Database Impact
- **WARNING**: This will completely replace your current database
- All existing data will be lost
- The process cannot be undone
- Always backup current data before restoration
