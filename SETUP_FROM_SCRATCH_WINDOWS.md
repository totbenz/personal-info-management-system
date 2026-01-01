# HRIS / Personal Info Management System — Fresh Windows Setup (From Zero)

This guide is for setting up the project on a **brand-new Windows laptop**.

## 1) What this project uses (based on the codebase)

- **Backend**: Laravel 10 (`laravel/framework:^10.0`)
- **PHP**: `^8.1` (required by `composer.json`)
- **Frontend build**: Vite (`vite:^4`) + TailwindCSS (`tailwindcss:^3.1`)
- **Dynamic UI**: Livewire v3 (`livewire/livewire:^3.4`) + WireUI (`wireui/wireui`)
- **Database**: MySQL (default `.env.example` uses `DB_CONNECTION=mysql`)
- **Extra features**:
  - PDF generation: dompdf + mpdf
  - Excel/Spreadsheet: maatwebsite/excel + phpspreadsheet
  - Word docs: phpoffice/phpword
  - Optional PDF via wkhtmltopdf: `barryvdh/laravel-snappy` (requires installing wkhtmltopdf if you use it)

## 2) Install the required software (fresh laptop)

### A. Core tools

1. **Google Chrome** (recommended for dev/testing)
2. **Git**
   - Download: https://git-scm.com/download/win
   - Verify:
     - `git --version`

3. **GitHub Desktop** (optional but beginner-friendly)
   - Download: https://desktop.github.com/

4. **VS Code** (recommended IDE)
   - Download: https://code.visualstudio.com/
   - Recommended extensions:
     - PHP Intelephense
     - Laravel Blade Snippets
     - Laravel Extra Intellisense
     - Tailwind CSS IntelliSense

### B. Local web server + PHP + MySQL

You have 2 common options. Pick **one**.

#### Option 1 (recommended): Laragon

- Download: https://laragon.org/download/
- Install Laragon (Full)
- In Laragon:
  - Start **Apache/Nginx**
  - Start **MySQL**
  - Confirm PHP version is **8.1+** (Laragon can switch versions)

#### Option 2: XAMPP

- Download: https://www.apachefriends.org/
- Ensure PHP version is **8.1+** (many XAMPP builds may be behind)

### C. Composer (PHP dependency manager)

- Download Composer for Windows: https://getcomposer.org/download/
- Verify:
  - `composer --version`

### D. Node.js (for Vite + Tailwind)

- Install Node.js **LTS** (recommended)
  - Download: https://nodejs.org/
- Verify:
  - `node -v`
  - `npm -v`

## 3) Get the project source code

### Option A: Git clone (recommended)

1. Create a folder for projects, for example:
   - `C:\Projects`

2. Clone the repository:

```bash
git clone <YOUR_REPO_URL_HERE>
```

3. Go into the project folder:

```bash
cd personal-info-management-system
```

### Option B: GitHub Desktop

1. Open GitHub Desktop
2. File -> Clone repository
3. Choose local path

## 4) Configure the environment file

This repo includes `.env.example`.

1. Copy `.env.example` to `.env`
   - If it doesn’t exist yet:

```bash
copy .env.example .env
```

2. Open `.env` and set:

- **APP_NAME**
- **APP_URL** (for local dev you can keep `http://localhost`)
- **DB_DATABASE**, **DB_USERNAME**, **DB_PASSWORD**

Example (local MySQL with Laragon default):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personal_info_management_system
DB_USERNAME=root
DB_PASSWORD=
```

## 5) Create the database

Using Laragon:

1. Open Laragon
2. Click **Database** (or open phpMyAdmin)
3. Create a database:
   - `personal_info_management_system`

(Use the same name you placed in `.env`.)

## 6) Install PHP dependencies (Composer)

From the project root:

```bash
composer install
```

If Composer fails, confirm:
- PHP is 8.1+
- PHP extensions enabled: `openssl`, `pdo_mysql`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `zip`, `fileinfo` (common Laravel requirements)

## 7) Install frontend dependencies (Node)

```bash
npm install
```

## 8) Generate Laravel app key

```bash
php artisan key:generate
```

## 9) Run migrations + seeders

This project includes a lot of seeders (see `database/seeders/DatabaseSeeder.php`).

Run:

```bash
php artisan migrate --seed
```

If you need a clean reset (WARNING: deletes tables/data):

```bash
php artisan migrate:fresh --seed
```

### Default seeded users

From `database/seeders/UserSeeder.php`:

- Admin:
  - `admin@mailinator.com` / `Password`
- School Head:
  - `schoolhead@mailinator.com` / `Password`
- Teacher:
  - `teacher@mailinator.com` / `Password`
- Non-teaching:
  - `nonteaching@mailinator.com` / `Password`

## 10) Storage symlink (uploads)

```bash
php artisan storage:link
```

## 11) Run the project (dev mode)

You typically run **two terminals**.

### Terminal A — Laravel backend

```bash
php artisan serve
```

This usually serves at:
- `http://127.0.0.1:8000`

### Terminal B — Vite frontend

```bash
npm run dev
```

## 12) Common operational commands

### Clear caches (when things get weird)

```bash
php artisan optimize:clear
```

### Run queue (only if queue driver is not sync)

If you change `.env` to `QUEUE_CONNECTION=database` or similar:

```bash
php artisan queue:work
```

### Run tests

```bash
php artisan test
```

## 13) Backup / Restore features included in this repo (optional)

This repo includes backup/recovery docs:

- `CSV_EXPORT_IMPORT_DOCUMENTATION.md`
- `DB_BACKUP.md`
- `DISASTER_RECOVERY_QUICK_START.md`
- `RECOVERY_SYSTEM_README.md`

### CSV export example

```bash
php artisan db:csv export
```

## 14) Troubleshooting

### A. "Class not found" / autoload issues

```bash
composer dump-autoload
php artisan optimize:clear
```

### B. Vite/Tailwind not updating

- Stop Vite and run again:

```bash
npm run dev
```

### C. Database connection errors

- Confirm MySQL is running
- Confirm `.env` DB values match the database you created
- Confirm port 3306

### D. PDF Snappy / wkhtmltopdf (only if you use it)

`barryvdh/laravel-snappy` typically requires installing **wkhtmltopdf**.
If you run into errors like "wkhtmltopdf not found", install it and configure the binary path.

## 15) Recommended “clean setup” checklist

- Install Git
- Install Laragon (PHP 8.1+ + MySQL)
- Install Composer
- Install Node.js LTS
- Clone repo
- Copy `.env.example` -> `.env`
- Create MySQL database
- `composer install`
- `npm install`
- `php artisan key:generate`
- `php artisan migrate --seed`
- `php artisan storage:link`
- Run `php artisan serve` + `npm run dev`

---

If you want, tell me whether you deploy this to a shared hosting / VPS / Windows server, and I can write a separate **production deployment guide** (Apache/Nginx, SSL, queues, scheduler, backups).
