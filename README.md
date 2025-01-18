# Banpay - Challenge

**Overview**

The API is designed to manage users with role-based access and integrates with the Studio Ghibli API for data retrieval based on user roles.

- ### [Installation](#installation)
- ### [Documentation](#documentation)

# Installation

### Instructions

Three methods are available to set up the project:

- #### [Traditional Setup (Local Environment)](#traditional-setup-local-environment) optional
- #### [Using Laravel Sail (Docker)](#using-laravel-sail-docker) default main branch
- #### [Using Docker](#using-docker) optional

> uncomment the environment variables for the Database in the .env.example file depending on the environment of your choice

---

## Traditional Setup (Local Environment)

### Prerequisites:

- PHP `>=` 8.2
- Composer
- MySQL `>=` 8.4 / MariaDB `>=` 10
- Node.js and npm latests version

### Installation Steps

Clone the repository

```shell
git clone git@github.com:ArmCM/api-ghibli.git
```

Enter the project directory `cd api-ghibli` and Install dependencies

```shell
composer install
```
Set up environment variables - copy `.env.example`

```shell
cp .env.example .env
```
Setup database in `.env` file

```shell
  DB_DATABASE=your-db-name
  DB_USERNAME=your-user
  DB_PASSWORD=your-password
```

Create app key

```shell
php artisan key:generate
```
Run the migrations

```shell
php artisan migrate
```

Seed the database with dummy data and create roles and permissions

```shell
php artisan db:seed
```
(**OPTIONAL**) In case you do not have a local server

```shell
php artisan serve
```

---
## Using Laravel Sail (Docker)

### Requirements:

- Docker Desktop

### Installation Steps

Clone the repository

```shell
git clone git@github.com:ArmCM/api-ghibli.git
```
Install project dependencies using Sail:

```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

Initialize Docker at this point

Set up environment variables:

```shell
cp .env.example .env
```
Setup database in `.env` file

```shell
  DB_DATABASE=api
  DB_USERNAME=beta
  DB_PASSWORD=password
```

Start containers:

```shell
./vendor/bin/sail up -d
```

Generate application key

```shell
./vendor/bin/sail artisan key:generate
```

Install dependencies

```shell
./vendor/bin/sail composer install
```

Run migrations

```shell
./vendor/bin/sail artisan migrate
```

Seed the database with dummy data

```
   ./vendor/bin/sail artisan db:seed
```

### **The application will be available at** http://localhost:8000 🚀


Run Tests 🧪

```shell
./vendor/bin/sail artisan test
```

Stop containers ⚠️

```shell
./vendor/bin/sail down -v
```

---

## Using Docker

### Requirements

- Docker Desktop
 
switch to branch `feature/docker_compose` `->` 
https://github.com/ArmCM/api-ghibli/pull/14

use `docker-compose.yml` from this branch ⚠️

### Installation Steps

Clone the repository

```shell
  git clone git@github.com:ArmCM/api-ghibli.git
```

Set up environment variables:

```shell
  cp .env.example .env
```
Initialize Docker at this point

Build and start containers:

```shell
  docker-compose up -d --build
```
Install dependencies:

```shell
  docker-compose exec app composer install
```

Generate application key:

```shell
    docker-compose exec app php artisan key:generate
```

Run migrations

```shell
    docker-compose exec app php artisan migrate
```
Seed the database with dummy data and create roles and permissions

```shell
    docker-compose exec app php artisan db:seed
```

### **The application will be available at** http://localhost:8000 🚀

Run Tests 🧪

```shell
    docker-compose exec app php artisan test
```

Stop and remove containers

```shell
docker-compose down -v
```

## Documentation

to test the endpoints I send an invitation to postman's workspace to this e-mail address `guillermo.rodriguez@banpay.com`
