# 🚨 Disaster Recovery Instructions

## Overview

This document provides step-by-step instructions for recovering your Personal Information Management System in case of database corruption, hacking, or complete system failure.

## ⚠️ IMPORTANT WARNINGS

-   **NEVER** attempt recovery on a production system without proper testing
-   **ALWAYS** verify backup integrity before restoration
-   **DOCUMENT** every step taken during recovery
-   **TEST** recovery procedures in a safe environment first

## 🔄 Recovery Scenarios

### Scenario 1: Database Corruption (Tables exist but data is corrupted)

**Symptoms:**

-   Application errors related to data integrity
-   Missing or corrupted records
-   Foreign key constraint violations

**Recovery Steps:**

1. **Stop the application** to prevent further corruption
2. **Create a backup** of the current corrupted state
3. **Identify the corruption** using database tools
4. **Restore from recent backup** using the restore command

```bash
# Create backup of current state (for analysis)
php artisan backup:database --type=full

# Restore from recent backup
php artisan restore:database latest --force
```

### Scenario 2: Complete Database Loss (Tables and data gone)

**Symptoms:**

-   Database connection errors
-   "Table doesn't exist" errors
-   Complete application failure

**Recovery Steps:**

1. **Verify database server** is running and accessible
2. **Check backup availability** in storage/app/backups/
3. **Restore database structure** first, then data
4. **Verify application functionality**

```bash
# Check available backups
ls -la storage/app/backups/

# Restore database structure first
php artisan restore:database latest --type=structure --force

# Then restore data
php artisan restore:database latest --type=data --force
```

### Scenario 3: CSV Export Recovery (Last resort)

**Symptoms:**

-   No SQL backups available
-   Only CSV exports exist
-   Need to rebuild from scratch

**Recovery Steps:**

1. **Ensure database structure** exists (run migrations)
2. **Import from CSV export** using the import command
3. **Verify data integrity** after import

```bash
# Run migrations to recreate structure
php artisan migrate:fresh

# Import from CSV export
php artisan import:csv path/to/your/csv_export.zip --force --truncate
```

## 🛠️ Recovery Commands Reference

### Backup Commands

```bash
# Create full backup
php artisan backup:database

# Create structure-only backup
php artisan backup:database --type=structure

# Create data-only backup
php artisan backup:database --type=data

# Create uncompressed backup
php artisan backup:database --compress=false
```

### Restore Commands

```bash
# Restore from specific backup file
php artisan restore:database storage/app/backups/db_full_2024-01-01_12-00-00.sql

# Restore from latest backup
php artisan restore:database latest

# Dry run (see what would be restored)
php artisan restore:database latest --dry-run

# Force restore without confirmation
php artisan restore:database latest --force
```

### CSV Import Commands

```bash
# Import from CSV export
php artisan import:csv path/to/csv_export.zip

# Dry run import
php artisan import:csv path/to/csv_export.zip --dry-run

# Force import without confirmation
php artisan import:csv path/to/csv_export.zip --force

# Skip specific tables
php artisan import:csv path/to/csv_export.zip --skip-tables=users,password_reset_tokens

# Truncate existing data before import
php artisan import:csv path/to/csv_export.zip --truncate
```

## 📋 Pre-Recovery Checklist

Before starting recovery:

-   [ ] **Document the problem** - What happened? When? How?
-   [ ] **Assess the damage** - What's working? What's broken?
-   [ ] **Identify the cause** - Was it hardware, software, or human error?
-   [ ] **Check backup status** - Are backups recent and accessible?
-   [ ] **Notify stakeholders** - Inform users about potential downtime
-   [ ] **Prepare recovery environment** - Ensure you have access to tools

## 🔍 Post-Recovery Verification

After recovery:

-   [ ] **Test database connectivity** - Can the app connect to the database?
-   [ ] **Verify critical tables** - Do users, personnels, schools tables exist?
-   [ ] **Check data integrity** - Are foreign key relationships intact?
-   [ ] **Test application functionality** - Can users log in? Are forms working?
-   [ ] **Verify user access** - Can admin users access the system?
-   [ ] **Check system logs** - Are there any new errors?

## 🚨 Emergency Contacts

**System Administrator:** [Your Name] - [Your Email] - [Your Phone]
**Database Administrator:** [DBA Name] - [DBA Email] - [DBA Phone]
**Hosting Provider:** [Provider Name] - [Support Phone] - [Support Email]

## 📚 Additional Resources

-   **Laravel Documentation:** https://laravel.com/docs
-   **MySQL Documentation:** https://dev.mysql.com/doc/
-   **Backup Best Practices:** https://www.mysql.com/why-mysql/white-papers/
-   **Disaster Recovery Planning:** [Your Company's DR Plan]

## 🔄 Recovery Testing Schedule

**Monthly:**

-   Test backup creation and restoration
-   Verify backup file integrity
-   Test CSV export/import functionality

**Quarterly:**

-   Full disaster recovery simulation
-   Test recovery time objectives (RTO)
-   Update recovery procedures

**Annually:**

-   Review and update this document
-   Train team on recovery procedures
-   Update emergency contacts

---

**Last Updated:** [Current Date]
**Next Review:** [Next Review Date]
**Document Version:** 1.0
