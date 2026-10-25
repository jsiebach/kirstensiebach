<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)->in('Feature', 'Unit', 'Browser');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Open the admin login page once its form is interactive.
 *
 * Filament renders the form server-side, then Alpine initializes each field.
 * Typing before that happens gets wiped, so wait for the password field's
 * Alpine component instead of a fixed delay or network idle.
 */
function visitAdminLogin($test)
{
    $page = $test->visit('/admin/login')->waitForText('Sign in');

    $deadline = microtime(true) + 10;
    while (! $page->script("() => !!document.getElementById('form.password')?.closest('[x-data]')?._x_dataStack")) {
        if (microtime(true) > $deadline) {
            throw new RuntimeException('The admin login form never became interactive.');
        }

        usleep(50_000);
    }

    return $page;
}

/**
 * Log in as the given admin and land on the dashboard.
 *
 * Navigates to /admin explicitly rather than trusting the post-login redirect,
 * which can follow a stale "intended" URL left by the previous browser test.
 */
function loginAsAdmin($test, $admin)
{
    return visitAdminLogin($test)
        ->type('#form\\.email', $admin->email)
        ->type('#form\\.password', 'password')
        ->submit()
        ->waitForText('Dashboard')
        ->navigate('/admin')
        ->waitForText('Dashboard');
}

/*
|--------------------------------------------------------------------------
| Uses
|--------------------------------------------------------------------------
|
| This section defines the traits that are automatically applied to test cases.
|
*/

uses(RefreshDatabase::class)->in('Feature', 'Unit', 'Browser');
