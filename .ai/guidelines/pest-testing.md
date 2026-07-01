# Opinionated Pest Testing 4

## When to Apply

Activate this skill when:

- Creating new tests (unit, feature, or browser)
- Modifying existing tests
- Debugging test failures
- Working with browser testing or smoke testing
- Writing architecture tests or visual regression tests

This document contains guidelines for writing feature tests using Pest 4 in Laravel applications, based on this project's established conventions.

## Core Testing Principles

### Use `it()` Function

Always use `it()` instead of `test()` for better test readability:

```php
// ✅ Preferred
it('loads the page correctly', function () {
    // ...
});

// ❌ Avoid
test('loads the page correctly', function () {
    // ...
});
```

### Pest Function Imports

Import Laravel testing functions from `Pest\Laravel` namespace instead of using `test()->`:

```php
use function Pest\Laravel\{get, post, actingAs, assertAuthenticatedAs, assertGuest};

it('performs login', function () {
    actingAs($user)
        ->get(route('login'))
        ->assertOk();
});
```

### Chained Assertions

Chain response assertions to make code less verbose:

```php
// ✅ Preferred
get(route('login'))
    ->assertOk()
    ->assertSee('Welcome');

// ❌ Avoid
$response = get(route('login'));
$response->assertOk();
$response->assertSee('Welcome');
```

## Route and Data Handling

### Route Variables

Always use `$route` variables when `route()` function receives array parameters:

```php
// ✅ Use route() directly (no array parameters)
get(route('login'));

// ✅ Use $route variable when array parameters present
$route = route('users.show', ['user' => $user->id]);
get($route);
```

### Payload Variables

Always use `$payload` variables for POST request data:

```php
// ✅ Preferred
$payload = [
    'email' => $user->email,
    'password' => 'password',
];

post(route('login.store'), $payload);

// ❌ Avoid
post(route('login.store'), [
    'email' => $user->email,
    'password' => 'password',
]);
```

## Test Structure

### Test Naming

Write test descriptions in natural language format:

```php
it('redirects authenticated users from login page');
it('preserves email field on failed login');
it('requires email field for validation');
```

### Data Setup

Use factories consistently with the `RefreshDatabase` trait:

```php
it('creates user successfully', function () {
    $user = User::factory()->create();
    // Test with $user...
});
```

## Authentication Testing

### Assertions

Use appropriate authentication assertions:

```php
// ✅ Authenticated state
assertAuthenticatedAs($user);

// ✅ Guest state
assertGuest();

// ✅ Acting as user
actingAs($user)->get(route('protected.page'));
```

## Code Formatting

### Import Organization

Group imports by type with single blank lines:

```php
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\{get, post, actingAs};
```

## Common Patterns

### Form Submission Testing

```php
it('processes form submission', function () {
    $user = User::factory()->create();
    $payload = ['email' => $user->email];

    post(route('form.submit'), $payload)
        ->assertRedirect('/success')
        ->assertSessionHas('success');

    assertAuthenticatedAs($user);
});
```

### Validation Error Testing

```php
it('fails validation with invalid data', function () {
    $payload = ['email' => 'invalid-email'];

    post(route('form.submit'), $payload)
        ->assertRedirect()
        ->assertSessionHasErrors('email');

    assertGuest();
});
```

### Route Protection Testing

```php
it('redirects unauthenticated users', function () {
    get(route('protected.page'))
        ->assertRedirect(route('login'));
});
```

## Best Practices

1. **One assertion per test** when possible, but chain related assertions
2. **Descriptive test names** that explain the behavior
3. **Factory usage** for creating test data
4. **Consistent formatting** with Laravel Pint
5. **Use proper helpers** for the application type (Inertia, API, etc.)
6. **Test both success and failure** scenarios
7. **Include edge cases** and validation scenarios