# Command Execution Standards

## Overview
This project runs commands **directly in the repo root** without Docker or Laravel Sail wrappers. All development tools (PHP, Composer, NPM, Pest, etc.) are installed and available in the local environment.

## Critical Rules

### ✅ ALWAYS Run Commands Directly

**Correct Command Pattern:**
```bash
# Run commands directly from the repo root
composer [command]
npm [command]
php artisan [command]
./vendor/bin/pest [options]
```

**Common Commands:**
```bash
# Composer
composer install
composer update
composer require [package]

# NPM
npm install
npm run build
npm run dev

# Artisan
php artisan migrate
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Pest Tests
./vendor/bin/pest
./vendor/bin/pest --filter=TestName
./vendor/bin/pest tests/Unit
```

### ❌ NEVER Use Sail or Docker Wrappers

**DO NOT use these commands:**
```bash
# ❌ WRONG - Do not use Sail
COMPOSE_FILE=docker-compose.local.yml ./vendor/bin/sail [command]
./vendor/bin/sail [command]

# ❌ WRONG - Do not use docker
docker-compose up
docker exec app php artisan ...

# ✅ CORRECT - Run directly
composer install
php artisan migrate
./vendor/bin/pest
```

## Environment

**Requirements:**
- PHP 8.4+ installed locally
- Composer installed locally
- Node.js 22+ installed locally
- MySQL/PostgreSQL database (configured in `.env`)

**Database Configuration:**
Set up your local database connection in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Common Tasks

### Installing Dependencies
```bash
# Composer dependencies
composer install
composer update

# NPM dependencies
npm install

# Playwright for browser tests
npx playwright install chromium
```

### Running Laravel Commands
```bash
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Create files
php artisan make:model MyModel
php artisan make:migration create_my_table
```

### Database Access
```bash
# Access MySQL CLI (use your local mysql client)
mysql -u username -p database_name

# Run tinker
php artisan tinker
```

### Running Tests
```bash
# Run all Pest tests
./vendor/bin/pest

# Run specific test suite
./vendor/bin/pest tests/Unit
./vendor/bin/pest tests/Feature
./vendor/bin/pest tests/Browser

# Run specific test file
./vendor/bin/pest tests/Unit/ExampleTest.php

# Run with coverage
./vendor/bin/pest --coverage

# Run with specific filter
./vendor/bin/pest --filter=TestName
```

### Building Assets
```bash
# Development build
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

### Code Quality
```bash
# Run Laravel Pint formatter
./vendor/bin/pint

# Run Pint on specific directory
./vendor/bin/pint app/
./vendor/bin/pint tests/
```

## Troubleshooting

### Dependencies Not Found
```bash
# Reinstall composer dependencies
rm -rf vendor
composer install

# Reinstall npm dependencies
rm -rf node_modules
npm install
```

### Database Connection Issues
```bash
# Verify database credentials in .env
cat .env | grep DB_

# Test database connection
php artisan tinker
# Then run: DB::connection()->getPdo();
```

### Cache Issues
```bash
# Clear all Laravel caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

# Regenerate autoload files
composer dump-autoload
```

### Permission Issues
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

## Agent Guidelines

When working with this codebase:

1. **ALWAYS** run commands directly in the repo root
2. **NEVER** use Docker, docker-compose, or Laravel Sail commands
3. **ALWAYS** use direct paths for tools (e.g., `./vendor/bin/pest` not `sail test`)
4. For Artisan commands, use `php artisan [command]`
5. For Composer, use `composer [command]`
6. For NPM, use `npm [command]`
7. For Pest tests, use `./vendor/bin/pest [options]`

## Quick Reference

```bash
# Install dependencies
composer install
npm install

# Run tests
./vendor/bin/pest

# Clear caches
php artisan config:clear && php artisan route:clear && php artisan cache:clear

# Build assets
npm run build

# Format code
./vendor/bin/pint

# Run migrations
php artisan migrate
```
