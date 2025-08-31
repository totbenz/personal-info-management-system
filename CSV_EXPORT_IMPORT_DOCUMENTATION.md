 # 📊 CSV Export/Import CLI Documentation

## 🎯 **What This System Does**

This system allows you to export your entire database to CSV files and restore it back using simple command-line commands. Perfect for:

-   **Daily backups** of your database
-   **Disaster recovery** when data is lost
-   **Data migration** between environments
-   **Testing** with real data snapshots

---

## 🚀 **Quick Start Commands**

### **Export Database (Backup)**

```bash
php artisan db:csv export
```

-   Exports all database tables to CSV files
-   Creates a ZIP file containing all CSV files
-   Saves to: `storage/app/exports/db_export_[timestamp]/database_export.zip`

### **Import Database (Restore)**

```bash
php artisan db:csv import --file="path/to/export.zip"
```

-   Imports all data from CSV files back to database
-   Automatically handles table dependencies
-   Restores your database exactly as it was

---

## 📋 **Complete Workflow Example**

### **Step 1: Create Backup**

```bash
php artisan db:csv export
```

**Output:**

```
Starting database export...
✓ Export completed successfully!
📊 Tables exported: 37
📁 Export location: storage/app/exports/db_export_2025-08-31_05-52-37/
```

### **Step 2: Clear Database (Optional)**

```bash
php artisan migrate:fresh
```

_This removes all data and recreates empty tables_

### **Step 3: Restore from Backup**

```bash
php artisan db:csv import --file="storage/app/exports/db_export_2025-08-31_05-52-37/database_export.zip"
```

**Output:**

```
Starting database import...
✓ Import completed!
📊 Tables imported: 31
```

---

## 🔧 **How It Works**

### **Export Process:**

1. **Scans Database:** Automatically discovers all tables
2. **Creates CSV Files:** Each table becomes a separate CSV file
3. **Handles Data:** Exports all rows with proper column headers
4. **Zips Everything:** Packages all CSV files into one ZIP file
5. **Cleanup:** Removes temporary files automatically

### **Import Process:**

1. **Extracts ZIP:** Unzips the backup file
2. **Smart Order:** Imports tables in dependency order (parent tables first)
3. **Data Validation:** Handles empty values and data types
4. **Bulk Insert:** Imports data efficiently in chunks
5. **Cleanup:** Removes temporary files after import

---

## 📁 **File Locations**

### **Export Files:**

-   **CSV Files:** `storage/app/exports/db_export_[timestamp]/`
-   **ZIP File:** `storage/app/exports/db_export_[timestamp]/database_export.zip`

### **Temporary Files:**

-   **Import Temp:** `storage/app/temp/import_[timestamp]/`
-   **Auto-cleanup:** Temporary files are removed after operations

---

## ⚠️ **Important Notes**

### **Before Import:**

-   Make sure your database structure matches the export
-   Run `php artisan migrate:fresh` if you want a clean start
-   The system automatically handles foreign key constraints

### **Data Integrity:**

-   All relationships between tables are preserved
-   Empty values are handled properly
-   Data types are maintained during import

### **Performance:**

-   Large datasets are processed in chunks (1000 rows at a time)
-   Import order is optimized for dependencies
-   Foreign key checks are temporarily disabled during import

---

## 🆘 **Troubleshooting**

### **Common Issues:**

**"File not found" error:**

-   Check the file path is correct
-   Make sure the ZIP file exists
-   Use absolute paths if needed

**Import fails on specific tables:**

-   Check database structure matches
-   Ensure all required tables exist
-   Verify foreign key relationships

**Memory issues with large exports:**

-   The system processes data in chunks
-   Increase PHP memory limit if needed
-   Check available disk space

---

## 📚 **Command Reference**

### **Export Command:**

```bash
php artisan db:csv export
```

**Options:** None required

### **Import Command:**

```bash
php artisan db:csv import --file="path/to/file.zip"
```

**Required Options:**

-   `--file`: Path to the ZIP file containing CSV exports

---

## 🔄 **Use Cases**

### **Daily Backup:**

```bash
# Every morning
php artisan db:csv export
# Save the ZIP file to safe location
```

### **Disaster Recovery:**

```bash
# After database crash
php artisan migrate:fresh
php artisan db:csv import --file="backup.zip"
```

### **Testing:**

```bash
# Export production data
php artisan db:csv export

# Import to test environment
php artisan db:csv import --file="production_backup.zip"
```

### **Data Migration:**

```bash
# Export from old server
php artisan db:csv export

# Import to new server
php artisan db:csv import --file="migration_backup.zip"
```

---

## 📞 **Support**

If you encounter issues:

1. Check the error messages in the command output
2. Verify file paths and permissions
3. Ensure database structure is compatible
4. Check available disk space and memory

---

_This documentation covers the basic usage of the CSV Export/Import system. The system is designed to be simple and reliable for everyday database backup and restore operations._
