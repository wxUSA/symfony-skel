# Symfony PHP Framework Skeleton Application

A minimal Symfony 7.4 skeleton application with custom PDO database abstraction and PHP+Twig template support. 
Includes sane defauts for several config files, saving time on pitfalls with modern RHEL servers and confusing documentation.

## Installation

1. **Install dependencies:**
   ```bash
   composer install
   ```

2. **Configure environment:**
   Copy `.env` and configure your database credentials:
   ```bash
   cp env-sample .env.local
   vi .env.local
   ```
   
   Set the database connection parameters:
   ```env
   DATABASE_DRIVER=mysql
   DATABASE_HOST=localhost
   DATABASE_USER=your_user
   DATABASE_PASS=your_password
   DATABASE_DB=your_database
   DATABASE_SSL=false
   ```

3. **Initialize database schema:**
   ```bash
   composer setup-db
   ```
   
   This creates the `sessions` table required for PdoSessionHandler in production.

## Usage

### Development Server
```bash
symfony serve
# or
php -S localhost:8000 -t public/
```

### Clear Cache
```bash
composer cache-clear
```

## Environment Configuration

- **Development:** Uses file-based sessions (no database required)
- **Production:** Uses PdoSessionHandler with database sessions

To use database sessions in development, move the PdoSessionHandler configuration from `config/services_prod.yaml` to `config/services.yaml`.

## Features

- Symfony 7.4 with PHP 8.2+
- Custom MyPDO database abstraction layer
- Twig and PHP template support (nyrodev/php-template-bundle)
- Database-backed session option
- Web Debug Toolbar & Symfony Profiler in development
