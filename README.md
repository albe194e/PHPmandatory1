# Company Management System

A PHP-based web application for managing company employees, departments, and projects.

## Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (optional, for future dependencies)

## Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/albe194e/PHPmandatory1.git
cd CompanyRepo
```

### 2. Database Setup

1. Create a MySQL database named `company`
2. Import the database schema:

```bash
mysql -u root -p < data/company.sql
```

Alternatively, you can use a database management tool like phpMyAdmin to import the `data/company.sql` file.

### 3. Configure Database Connection

If needed, update the database credentials in `src/db_credentials.php`:

```php
protected string $host = 'localhost';
protected string $dbname = 'company';
protected string $user = 'username';
protected string $password = 'password';
```

Replace these values with your actual database connection details.

### 4. Web Server Configuration

#### Using PHP's Built-in Server (for development)

```bash
php -S localhost:8000
```

Then access the application at http://localhost:8000

#### Using Apache

1. Configure your virtual host to point to the project directory
2. Ensure the DocumentRoot is set to the project root
3. Enable mod_rewrite if you plan to use URL rewriting

#### Using Nginx

Configure your server block to point to the project directory and properly handle PHP files.

### 5. Verify Installation

1. Open your browser and navigate to the application URL
2. You should see the employee listing page
3. Test the functionality by:
   - Viewing employee details
   - Adding new employees
   - Editing existing employees
   - Managing departments and projects

## Project Structure

- `index.php` - Main entry point
- `src/` - Core PHP classes
  - `database.php` - Database connection handling
  - `employee.php` - Employee management
  - `department.php` - Department management
  - `project.php` - Project management
  - `logger.php` - Error logging
- `views/` - HTML templates
- `css/` - Stylesheets
- `data/` - Database schema

## Troubleshooting

### Database Connection Issues

- Verify your MySQL server is running
- Check the credentials in `src/db_credentials.php`
- Ensure the `company` database exists
