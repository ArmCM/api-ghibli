# Banpay - Challenge

**Overview**

The API is designed to manage users with role-based access and integrates with the Studio Ghibli API for data retrieval based on user roles.

- ### [Instalacion](#instalacion)
- ### [Documentacion](#documentacion)
- ### Comandos utiles



# Instalacion

### Instructions

Para levantar el proyecto se pone a disposicion 3 formas de hacerlo

- #### [Configuración Tradicional (entorno Local)](#configuración-tradicional)
- #### [Usando Laravel Sail (Docker)](#usando-laravel-sail-(Docker))
- #### [Usando Docker](#usando-docker)

---

## Configuración Tradicional (entorno Local)

### Prerequisites:

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js y npm

### pasos de instalacion

Clone the repository

```shell
  git clone git@github.com:ArmCM/api-ghibli.git
```

Entrar al proyecto `cd api-ghibli` e Install dependencies

```shell
  composer install
```
Definir archivo de variables de entorno - copiar`.env.example`

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

Seed the database with dummy data

```shell
  php artisan db:seed
```
(**OPTIONAL**) In case you do not have a local server

```shell
  php artisan run serve
```

---
## Usando Laravel Sail (Docker)

### Requisitos:

- Docker Desktop
- Docker Compose

### Pasos de Instalación

Instalar dependencias del proyecto usando Sail:

Clone the repository

```shell
  git clone git@github.com:ArmCM/api-ghibli.git
```
Instalar dependencias del proyecto usando Sail:

```shell
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
```

Inicializa Docker en este punto

Configurar variables de entorno:

```shell
  cp .env.example .env
```
Setup database in `.env` file

```shell
  DB_DATABASE=api
  DB_USERNAME=beta
  DB_PASSWORD=password
```

Iniciar contenedores:

```shell
    ./vendor/bin/sail up -d
```

Generar clave de aplicación

```shell
  ./vendor/bin/sail artisan key:generate
```

Instalar dependencias

```shell
    ./vendor/bin/sail composer install
```

Correr las migraciones

```shell
    ./vendor/bin/sail artisan migrate
```

Seed the database with dummy data

```
    ./vendor/bin/sail artisan db:seed
```

**La aplicación estará disponible en** http://localhost:8000 🚀


Ejecutar los Test 🧪

```shell
    ./vendor/bin/sail artisan test
```

Detener los contedores ⚠️

```shell
    ./vendor/bin/sail down -v
```

---

## Usando Docker

### Requisitos

- Docker
- Docker Compose

### Pasos de Instalación

Clone the repository

```shell
  git clone git@github.com:ArmCM/api-ghibli.git
```

Configurar variables de entorno:

```shell
  cp .env.example .env
```

Construir y levantar contenedores:

```shell
  docker-compose up -d --build
```
Instalar dependencias:

```shell
  docker-compose exec app composer install
```

Generar clave de aplicación:

```shell
    docker-compose exec app php artisan key:generate
```

Ejecutar migraciones

```shell
    docker-compose exec app php artisan migrate
```

Ejecutar los Test 🧪

```shell
    docker-compose exec app php artisan test
```

**La aplicación estará disponible en** http://localhost:8000 🚀

