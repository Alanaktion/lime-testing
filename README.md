# Lime Testing Suite

A simple testing application designed to help you collect, manage, and run your manual software tests.

## Requirements

- PHP 8.2+
- Composer
- Node LTS 20+
- MySQL 8 (or other Laravel-compatible RDBMS, untested)

## Setup

### Dependencies

For a local or development environment, clone the repo, then run:

```bash
composer install
pnpm install
pnpm run dev
```

For a production environment:

```bash
composer install --no-dev
pnpm install
pnpm run build
```

### Application setup

Once dependencies are installed and compiled, copy the `.env.example` file to `.env`. Enter your database connection details, and any other configuration you need to change, then run:

```bash
php artisan key:generate
php artisan migrate
```

If you're running a local/dev environment and don't want to use a web server, you can run `php artisan serve` to get a simple local instance on `http://localhost:8000`. For a production server, see the [Laravel Deployment documentation](https://laravel.com/docs/8.x/deployment).

## Usage

### Registration

Unless you disabled authentication in your `.env` file, you can register an account by visiting your running site.

If registration was disabled, use `php artisan tinker` to open a PHP session, then run:

```php
$user = new User();
$user->name = 'Example Name';
$user->email = 'user@example.com';
$user->password = Hash('a real password');
$user->save();
```

You can add and manage members of your teams from the web interface by clicking your team name in the navigation bar, then clicking Team Settings. You can create and manage as many teams as you need.

### Testing

Tests are collected in test suites, created and managed by your team. You can create, rename, and archive/restore test suites. Each test suite can contain any number of tests, which your testers can go through sequentially.

Each test should ideally contain a name, description, and list of steps to complete the test. Testers will be able to mark the test as passed, failed, or skipped while testing, and can comment on each test during each test run.
