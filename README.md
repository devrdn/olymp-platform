# Olymp Platform

## About

Olymp Platform is an automated platform for informatics competitions.

## Project prerequisites

 * composer : 2.4.4
 * symfony : 6.1
 * mariadb : 10.5.1
 * php : 8.1

## Installation and Launch

To install and launch platform, you need:

### Install
1. Install [Symfony](https://symfony.com/doc/current/setup.html) from official repository
2. Clone project from github
```shell
git clone https://github.com/devrdn/olymp-platform.git
```
3. Install dependencies
```shell
composer update
# or
php composer.phar update
```

### Database Migration
1. Connect your database in .env file:
```ini
# Example for mysql
DATABASE_URL="mysql://<user>:<password>@<ip>:<port>/<database>?serverVersion=<db-version>"
```
2. Create database
```shell
php bin/console doctrine:database:create
```
3. Make migration to database
```shell
php bin/console doctrine:migration:migrate
```


### Start Server
```shell
symfony server:start
```

## Todo (README.md)
1. Makefile description (install project)
2. Start with docker