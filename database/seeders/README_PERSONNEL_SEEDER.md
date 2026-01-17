# Personnel with Related Data Seeder

This seeder populates **ALL existing personnel** in the database with complete related information.

## What it creates:

For **EVERY personnel** already in the database, it adds (if missing):
- **2 Addresses** (permanent and residential)
- **1-2 Contact Persons** (emergency contacts)
- **Family Members**:
  - Father
  - Mother
  - Spouse (if married/widowed/separated)
  - 1-3 Children (if married)
- **Education Records**:
  - Elementary
  - Secondary
  - Graduate (College degree)
  - Graduate Studies (30% of personnel have this)
- **1-3 Civil Service Eligibilities** (licenses/certifications)
- **2-5 Work Experiences** (previous employment history)

## Important:

- This seeder **does NOT create new personnel**
- It only adds missing related data to existing personnel
- It checks for existing data and won't duplicate records
- Safe to run multiple times - it will only add missing data

## How to run:

### Option 1: Run only the personnel seeder
```bash
php artisan db:seed --class=PersonnelWithRelatedDataSeeder
```

### Option 2: Include in DatabaseSeeder
Add this line to your `DatabaseSeeder.php`:
```php
$this->call(PersonnelWithRelatedDataSeeder::class);
```

Then run:
```bash
php artisan db:seed
```

## Customization:

To change the number of personnel created, edit the seeder file:
```php
Personnel::factory(50)->create([  // Change 50 to your desired number
```

## Output:

The seeder will display:
- Progress for each personnel created
- Total counts for all created records

## Notes:

- All data is realistic and follows Philippine naming conventions
- Government ID numbers follow proper formats (TIN: 12 digits, SSS: 10 digits, etc.)
- Work experiences include both government and private sector jobs
- Education records follow the Philippine education system
- Family relationships are properly structured
