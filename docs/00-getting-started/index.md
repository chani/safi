# Getting Started with Safi

Safi is a PHP 8.5 microframework built on Pure Dependency Injection, isolated component architecture, and explicit composition root wiring.

---

## Requirements

- PHP 8.5 or higher
- Composer
- PHP extensions: `pdo`, `mbstring` (optional: `apcu`, `pdo_sqlite`)

---

## Installation

Clone the application skeleton and install dependencies via Composer:

```bash
git clone [https://github.com/chani/safi.git](https://github.com/chani/safi.git) my-app
cd my-app
composer install
```

---

## Configuration

Copy the default configuration file to create a local override:

```bash
cp config/config.php config/config.local.php
```

Edit `config/config.local.php` to adjust database paths or application settings:

```php
<?php

return [
    'app' => [
        'debug' => true,
    ],
    'db' => [
        'dsn' => 'sqlite:' . __DIR__ . '/../data/db/safi.db',
    ],
];
```

---

## Running the Application

Start the built-in PHP development server targeting the `public/` directory:

```bash
php -S localhost:8000 -t public/
```

Open `http://localhost:8000/hello` in a browser to verify installation.
