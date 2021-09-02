# Rankquirements

Rankquirements is a simple application to perform requirement prioritization task. This application was developed as part of my thesis. It facilitates Collaboration Value Oriented Prioritization method which was proposed in my thesis.

## Tech Stack
- Framework used is **Laravel 8.40.0** with **PHP 7.4.2**
- Database used is **MySQL**

## Requirements
- PHP 7.4.2 or newer
- MySQL 10.4.11-MariaDB or newer
- Composer 2.0.13 or newer

## Installation
1. Make a copy `.env.example` and change its name to `.env`
2. Customize `.env` file with your configuration
3. Create a new database with the same name as defined in `.env` file
4. Run these commands in terminal
```
composer install
composer update
php artisan storage:link
php artisan key:generate
php artisan config:cache
php artisan migrate
```
5. Congratulation, the application has been successfully installed. Run the application with this command
```
php artisan serve
```

## Author
Kurniandha Sukma Yunastrian
