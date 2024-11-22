# Olymp Platform Backend

## About

Olymp Platform is an automated platform for informatics competitions. The platform is designed to be easy to use and to
provide a wide range of features to help teachers and students manage their competitions.

## Project Requirements

* php 8.1
* postgresql 13
* composer

## Installation

1. Clone or download the repository
2. Go to the project directory `cd src`
3. Run `composer install` to install the dependencies
4. Copy the `.env.example` file to `.env` and configure the database connection
5. Generate the application key by running `php artisan key:generate`
6. Run the migrations with `php artisan migrate`
7. Run development server with `php artisan serve`