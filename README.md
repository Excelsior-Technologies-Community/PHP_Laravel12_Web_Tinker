# PHP_Laravel12_Web_Tinker

## Project Introduction

PHP_Laravel12_Web_Tinker is a Laravel 12 demonstration project that shows how to integrate and use a web-based Tinker console within a Laravel application.
It helps beginners understand Laravel project setup, Composer package installation, secure execution of database and Eloquent commands from the browser, and the difference between require and require-dev dependencies.
This implementation is inspired by the official Spatie Web Tinker package.

## Project Overview

This project provides a clear, step-by-step guide to installing, configuring, and safely using Web Tinker in Laravel 12.
It covers environment setup, package installation, asset publishing, executing interactive Laravel commands in the browser, and applying proper development-only security practices.
Overall, it serves as a simple learning reference for exploring Laravel through a web-based debugging interface.

------------------------------------------------------------------------

## Project Requirements

Make sure your system has:

-   PHP **8.2 or higher**
-   Composer
-   MySQL or SQLite database
-   XAMPP / Laragon / Local server
-   Basic Laravel knowledge

------------------------------------------------------------------------

# Step-by-Step Implementation

## Step 1 --- Create Laravel 12 Project

``` bash
composer create-project laravel/laravel PHP_Laravel12_Web_Tinker "12.*"
cd PHP_Laravel12_Web_Tinker
php artisan serve
```

Open in browser:

```bash
    http://127.0.0.1:8000
```

------------------------------------------------------------------------

## Step 2 --- Configure Environment (.env)

``` env
APP_NAME="Laravel Web Tinker"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
```

### Database Example (MySQL)

``` env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_tinker
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

``` bash
php artisan migrate
```

------------------------------------------------------------------------

## Step 3 --- Install Web Tinker Package (Dev Only)

``` bash
composer require spatie/laravel-web-tinker --dev
```

If Composer asks to move the package to **require-dev**, type:

```bash
    yes
```

------------------------------------------------------------------------

## Step 4 --- Install Web Tinker Assets

``` bash
php artisan web-tinker:install
```

This command:

-   Publishes frontend assets\
-   Creates `public/vendor/web-tinker`\


------------------------------------------------------------------------

## Step 5 --- (Optional) Publish Config File

``` bash
php artisan vendor:publish --provider="Spatie\WebTinker\WebTinkerServiceProvider" --tag="config"
```

------------------------------------------------------------------------

## Step 6 --- Access Web Tinker

``` bash
php artisan serve
```

Open:

```bash
    http://127.0.0.1:8000/tinker
```

------------------------------------------------------------------------

## Step 7 --- Test Example Commands


Create test user:

``` php
App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password')
]);
```

Count Users:

``` php
App\Models\User::count();
```

``` php
App\Models\User::all();
```

------------------------------------------------------------------------

##  Output

<img width="1830" height="1067" alt="Screenshot 2026-02-19 112213" src="https://github.com/user-attachments/assets/36b1be5d-1cd5-4f7d-a68f-a33cf8281d6c" />

------------------------------------------------------------------------

##  Project Structure

```
PHP_Laravel12_Web_Tinker/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   └── vendor/
│       └── web-tinker/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env
├── artisan
└── composer.json
```

------------------------------------------------------------------------

Your PHP_Laravel12_Web_Tinker Project is now ready!
