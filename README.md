<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Project Overview
This project is built with Laravel 11.

## Prerequisites

Ensure the following prerequisites are met before proceeding with the installation:

PHP 8.2 with the GD extension enabled
MySQL

## Installation Instructions
Follow these steps to set up and run the project:

Clone the Repository:
git clone https://github.com/touhedul/interview-task.git

Navigate to the Project Directory:
cd interview-task

Install Dependencies:
composer install

Copy Environment Configuration:
Duplicate the .env.example file and rename it to .env.

Create a Database:
Set up a MySQL database for the project.

Set Database Credentials:
Configure the .env file with appropriate database credentials.

Generate Application Key:
php artisan key:generate

Run Migrations and Seeders:
php artisan migrate --seed

Create Symbolic Link for Storage:
php artisan storage:link

Serve the Application:
php artisan serve

Install Frontend Dependencies:

Open a new terminal window and execute:
npm install

Compile Frontend Assets:
npm run dev


## Testing

To run only the Unit tests:
php artisan test --filter Unit

To run all the tests:
php artisan test
