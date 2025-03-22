# Personal Info Management System

## Overview
The Personal Info Management System is a web application designed to manage personnel information, including personal details, work information, and government-related data. This system aims to streamline the management of personnel records for educational institutions.

## Project Structure
The project is organized into several directories, each serving a specific purpose:

- **database/migrations**: Contains migration files for creating and modifying database tables.
  - `2024_03_30_150326_create_personnels_table.php`: Migration to create the `personnels` table with various columns for personal and work information.
  - `[timestamp]_add_salary_to_personnels_table.php`: Migration to add a `salary` column to the `personnels` table.

- **app**: Contains the core application logic, including models, controllers, and services.

- **bootstrap**: Used for bootstrapping the application, including loading configuration files and setting up the environment.

- **config**: Contains configuration files for the application, such as database settings and service providers.

- **public**: Contains public-facing files, including front-end assets and the entry point for the web server.

- **resources**: Contains views, raw assets (like CSS and JavaScript), and language files.

- **routes**: Contains route definitions for the application, mapping URLs to controllers.

- **storage**: Used for storing logs, compiled views, file uploads, and other application data.

- **tests**: Contains test cases for the application, ensuring that the application behaves as expected.

## Setup Instructions
1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Install the necessary dependencies using Composer.
4. Set up your `.env` file with the appropriate database configuration.
5. Run the migrations to create the necessary database tables:
   ```
   php artisan migrate
   ```
6. Start the local development server:
   ```
   php artisan serve
   ```

## Usage
Once the application is running, you can access it via your web browser at `http://localhost:8000`. You can manage personnel records, including adding, updating, and deleting entries.

## Contributing
Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.