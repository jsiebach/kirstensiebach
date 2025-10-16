# Pest v4 Testing Patterns

## Overview

This document outlines the testing patterns and standards used in this Laravel 11 + Filament 4 application using Pest v4 for testing.

## Test Organization

### Directory Structure

```
tests/
├── Unit/                  # Unit tests for isolated logic
│   ├── ExampleTest.php
│   └── Factories/         # Factory validation tests
│       └── FactoryTest.php
├── Feature/               # Feature tests organized by domain
│   ├── Pages/            # Page model tests
│   ├── Users/            # User and permission tests
│   ├── Research/         # Research model tests
│   ├── Publications/     # Publication model tests
│   ├── Press/            # Press model tests
│   └── TeamMembers/      # Team member tests
├── Browser/              # Browser tests (pest-plugin-browser)
│   ├── Admin/           # Admin panel tests
│   └── Frontend/        # Frontend display tests
├── Pest.php             # Pest configuration
├── TestCase.php         # Base test case
└── CreatesApplication.php # Application bootstrap trait
```

### Test Naming Conventions

- **Test Files**: Use `*Test.php` suffix (e.g., `UserResourceTest.php`)
- **Test Descriptions**: Use descriptive phrases starting with lowercase
  ```php
  test('user can be created with valid data', function () {
      // test code
  });
  ```

## Pest Configuration

### tests/Pest.php

```php
<?php

declare(strict_types=1);

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Extend TestCase for Feature and Unit tests
pest()->extend(TestCase::class)->in('Feature', 'Unit', 'Browser');

// Auto-apply RefreshDatabase trait
uses(RefreshDatabase::class)->in('Feature', 'Unit', 'Browser');
```

## Test Syntax Patterns

### Basic Test Structure

```php
test('descriptive test name', function (): void {
    // Arrange
    $user = User::factory()->create();

    // Act
    $result = $user->hasRole('admin');

    // Assert
    expect($result)->toBeTrue();
});
```

### Using Expectations

Pest v4 provides fluent expectation syntax:

```php
// Boolean assertions
expect($value)->toBeTrue();
expect($value)->toBeFalse();

// Type assertions
expect($value)->toBeInt();
expect($value)->toBeString();
expect($value)->toBeInstanceOf(Model::class);

// Equality assertions
expect($value)->toBe('expected');
expect($value)->toEqual($expected);
expect($value)->not->toBe('unexpected');

// String assertions
expect($string)->toContain('substring');
expect($string)->toStartWith('prefix');
expect($string)->toEndWith('suffix');

// Array/Collection assertions
expect($array)->toHaveCount(3);
expect($array)->toContain('value');
```

### HTTP Testing

```php
test('homepage loads successfully', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSeeText('Welcome');
});

test('admin dashboard requires authentication', function (): void {
    $response = $this->get('/admin');

    $response->assertRedirect('/admin/login');
});
```

### Database Testing

#### Using Factories

```php
test('research project can be created', function (): void {
    $researchPage = ResearchPage::factory()->create(['slug' => 'research']);

    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Mars Study',
    ]);

    expect($research->id)->toBeInt();
    expect($research->project_name)->toBe('Mars Study');
});
```

#### Using Factory States

```php
test('publication can be created as draft', function (): void {
    $publication = Publication::factory()->draft()->create();

    expect($publication->published)->toBeFalse();
});

test('research project with image', function (): void {
    $research = Research::factory()->withImage()->create();

    expect($research->image)->toBeString();
});
```

#### Testing File Uploads

```php
test('page image upload', function (): void {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('banner.jpg');
    $path = $file->store('pages', 'public');

    $homePage = HomePage::factory()->create();
    $homePage->content->banner = $path;
    $homePage->save();

    Storage::disk('public')->assertExists($path);
});
```

### Authentication and Authorization Testing

```php
test('admin user has correct role', function (): void {
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $user = User::factory()->create();
    $user->assignRole('admin');

    expect($user->hasRole('admin'))->toBeTrue();
});

test('non admin cannot access filament panel', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin');

    $response->assertStatus(403);
});
```

### Testing Model Relationships

```php
test('research project belongs to page', function (): void {
    $researchPage = ResearchPage::factory()->create();
    $research = Research::factory()->create(['page_id' => $researchPage->id]);

    expect($research->page)->toBeInstanceOf(Page::class);
    expect($research->page->id)->toBe($researchPage->id);
});
```

### Testing Model Scopes and Ordering

```php
test('publications are ordered by date published descending', function (): void {
    $page = PublicationsPage::factory()->create();

    $older = Publication::factory()->create([
        'page_id' => $page->id,
        'date_published' => '2022-01-01',
    ]);

    $newer = Publication::factory()->create([
        'page_id' => $page->id,
        'date_published' => '2024-01-01',
    ]);

    $publications = Publication::all();

    expect($publications->first()->id)->toBe($newer->id);
});
```

### Testing Model Casts

```php
test('publication date is cast to carbon instance', function (): void {
    $publication = Publication::factory()->create([
        'date_published' => '2024-06-15',
    ]);

    expect($publication->date_published)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($publication->date_published->format('Y-m-d'))->toBe('2024-06-15');
});
```

## Common Testing Patterns

### Arrange-Act-Assert Pattern

Always structure tests with clear sections:

```php
test('user can update their profile', function (): void {
    // Arrange - Set up test data
    $user = User::factory()->create(['name' => 'Original Name']);

    // Act - Perform the action being tested
    $user->update(['name' => 'New Name']);

    // Assert - Verify the expected outcome
    expect($user->fresh()->name)->toBe('New Name');
});
```

### Testing Exceptions

```php
test('email must be unique', function (): void {
    User::factory()->create(['email' => 'duplicate@example.com']);

    expect(fn () => User::create([
        'name' => 'Another User',
        'email' => 'duplicate@example.com',
        'password' => Hash::make('password'),
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});
```

### Setup and Teardown

Use `beforeEach()` for test setup:

```php
beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('authenticated user can access dashboard', function (): void {
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});
```

## Factory Usage

### Creating Models

```php
// Create single model
$user = User::factory()->create();

// Create with attributes
$user = User::factory()->create(['email' => 'test@example.com']);

// Create multiple models
$users = User::factory()->count(5)->create();

// Create without persisting
$user = User::factory()->make();
```

### Using Factory States

```php
// Published publication
$publication = Publication::factory()->published()->create();

// Active team member
$teamMember = TeamMember::factory()->active()->create();

// Featured research with image
$research = Research::factory()->featured()->withImage()->create();
```

## Best Practices

### 1. Test One Thing Per Test

```php
// Good - Tests one specific behavior
test('admin can create user', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post('/users', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
    ]);

    $response->assertStatus(201);
});

// Avoid - Tests multiple behaviors
test('user management', function (): void {
    // Creating, updating, deleting all in one test
});
```

### 2. Use Descriptive Test Names

```php
// Good
test('publication can be created with required fields', function () {});

// Avoid
test('publication test', function () {});
```

### 3. Use Factories for Test Data

```php
// Good - Uses factory
$user = User::factory()->create();

// Avoid - Manual creation
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
]);
```

### 4. Clean Up After Tests

The `RefreshDatabase` trait automatically handles database cleanup:

```php
uses(RefreshDatabase::class)->in('Feature', 'Unit');
```

### 5. Use Storage Fakes for File Testing

```php
test('handles file uploads', function (): void {
    Storage::fake('public');  // Always fake storage

    $file = UploadedFile::fake()->image('test.jpg');
    // ... test code

    Storage::disk('public')->assertExists($path);
});
```

## Running Tests

### Run All Tests

```bash
php artisan test
# or
vendor/bin/pest
```

### Run Specific Test Suites

```bash
# Unit tests only
vendor/bin/pest tests/Unit

# Feature tests only
vendor/bin/pest tests/Feature

# Specific test file
vendor/bin/pest tests/Feature/Users/UserResourceTest.php
```

### Run Tests with Coverage

```bash
php artisan test --coverage --min=80
```

### Filter Tests by Name

```bash
vendor/bin/pest --filter="admin"
```

## Code Formatting

All test code must follow Laravel Pint standards:

```bash
# Format all tests
vendor/bin/pint tests/

# Format specific directory
vendor/bin/pint tests/Feature/
```

## Type Declarations

Always use type declarations in tests:

```php
test('example test', function (): void {
    // Test code with void return type
});

beforeEach(function (): void {
    // Setup code
});
```

## Additional Resources

- [Pest Documentation](https://pestphp.com)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [Pest Plugin Laravel](https://pestphp.com/docs/plugins#laravel)
- [Pest Plugin Browser](https://pestphp.com/docs/plugins#browser)
