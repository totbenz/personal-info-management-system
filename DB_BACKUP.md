# Export all data to CSV

php artisan db:csv export

# Creates: storage/app/exports/db*export*[timestamp]/database_export.zip

# After migrate:fresh, import data

php artisan db:csv import --file="path/to/export.zip"

SIMPLE QUICK OPERATION

# Export data to CSV

php artisan db:csv export

# Export structure to SQL (existing system)

php artisan backup:database --type=structure

php artisan migrate:fresh

# Restore structure first

php artisan restore:database latest --type=structure --force

# Then import data from CSV

php artisan db:csv import --file="storage/app/exports/db*export*[timestamp]/database_export.zip"
