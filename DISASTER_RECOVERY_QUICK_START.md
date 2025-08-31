# 🚨 Disaster Recovery Quick Start Guide

## 🎯 **What We've Built:**

✅ **CSV Export** - Export all database tables to CSV files  
✅ **Automated Backups** - Daily database backups with compression  
✅ **Database Restore** - Restore from SQL backup files  
✅ **CSV Import** - Emergency recovery from CSV exports  
✅ **Automated Scheduling** - Daily backups at 2:00 AM

## 🚀 **Quick Commands Reference:**

### **1. Export Database to CSV (Web Interface)**

-   Log in as admin
-   Click "Export Database to CSV" in navigation menu
-   Download ZIP file containing all tables

### **2. Create Database Backup (Command Line)**

```bash
# Full backup (recommended)
php artisan backup:database

# Structure only
php artisan backup:database --type=structure

# Data only
php artisan backup:database --type=data

# Uncompressed backup
php artisan backup:database --compress=false
```

### **3. Restore Database (Command Line)**

```bash
# Dry run (see what would be restored)
php artisan restore:database latest --dry-run

# Restore from latest backup
php artisan restore:database latest --force

# Restore from specific file
php artisan restore:database storage/app/backups/db_full_2024-01-01_12-00-00.sql
```

### **4. Import from CSV (Emergency Recovery)**

```bash
# Analyze CSV export without importing
php artisan import:csv path/to/csv_export.zip --dry-run

# Import from CSV export
php artisan import:csv path/to/csv_export.zip --force --truncate

# Skip specific tables
php artisan import:csv path/to/csv_export.zip --skip-tables=users,password_reset_tokens
```

## 🔄 **Recovery Scenarios:**

### **Scenario 1: Quick Data Export**

```bash
# Use web interface - Export Database to CSV button
# This gives you all data in portable format
```

### **Scenario 2: Database Corruption**

```bash
# 1. Create backup of current state
php artisan backup:database --type=full

# 2. Restore from recent backup
php artisan restore:database latest --force
```

### **Scenario 3: Complete Database Loss**

```bash
# 1. Check available backups
ls storage/app/backups/

# 2. Restore structure first
php artisan restore:database latest --type=structure --force

# 3. Restore data
php artisan restore:database latest --type=data --force
```

### **Scenario 4: CSV Recovery (Last Resort)**

```bash
# 1. Ensure database structure exists
php artisan migrate:fresh

# 2. Import from CSV export
php artisan import:csv path/to/csv_export.zip --force --truncate
```

## 📁 **File Locations:**

-   **Backups:** `storage/app/backups/`
-   **CSV Exports:** Downloaded from web interface
-   **Recovery Docs:** `storage/docs/recovery-instructions.md`
-   **Configuration:** `config/backup.php`

## ⚠️ **Important Notes:**

1. **Backups run automatically** every day at 2:00 AM
2. **Old backups are cleaned up** after 30 days
3. **Always test recovery** in a safe environment first
4. **CSV export is your primary backup** - it's working perfectly!
5. **SQL backups are secondary** - for complete disaster recovery

## 🧪 **Testing Your System:**

### **Test 1: CSV Export**

-   Log in as admin
-   Click "Export Database to CSV"
-   Verify ZIP file downloads successfully

### **Test 2: Database Backup**

```bash
php artisan backup:database --type=structure --compress=false
# Should create a backup file in storage/app/backups/
```

### **Test 3: Restore Analysis**

```bash
php artisan restore:database latest --dry-run
# Should show backup analysis without making changes
```

## 🆘 **Emergency Recovery Steps:**

1. **Assess the situation** - What's broken?
2. **Check backup availability** - What backups exist?
3. **Choose recovery method** - SQL backup or CSV import?
4. **Execute recovery** - Use appropriate command
5. **Verify recovery** - Test application functionality

## 📞 **Need Help?**

-   **Check logs:** `storage/logs/laravel.log`
-   **Review docs:** `storage/docs/recovery-instructions.md`
-   **Test commands:** Use `--dry-run` flag first
-   **Check config:** `config/backup.php`

---

**🎉 Your disaster recovery system is now complete and ready!**

**Last Updated:** August 31, 2025  
**System Status:** ✅ Fully Operational
