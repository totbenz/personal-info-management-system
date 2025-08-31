# Step 1: Drop all tables (DANGER ZONE!)

php artisan migrate:fresh

# Step 2: Restore from your backup

php artisan restore:database latest --force

# Step 3: Test if everything works

php artisan tinker --execute="echo 'Testing...'; DB::table('users')->count(); echo 'Users restored!';"
