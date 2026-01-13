<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Illuminate\Database\Eloquent\Model;

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

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Unit');

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

expect()->extend('toBeOne', fn () =>
    // @phpstan-ignore-next-line
    $this->toBe(1));

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

/*function something()
{
    // ..
}*/

/**
 * Assert that a table record action is visible or hidden.
 *
 * @param  mixed  $livewire  The Livewire test instance
 * @param  \Illuminate\Database\Eloquent\Model  $record  The record to test
 * @param  string  $actionClass  The action class (e.g., EditAction::class)
 * @param  bool  $shouldBeVisible  Whether the action should be visible
 */
function assertTableRecordActionVisibility(
    $livewire,
    Model $record,
    string $actionClass,
    bool $shouldBeVisible
): void {
    $testAction = TestAction::make($actionClass)->table($record);

    if ($shouldBeVisible) {
        $livewire->assertActionVisible($testAction);
    } else {
        $livewire->assertActionHidden($testAction);
    }
}
