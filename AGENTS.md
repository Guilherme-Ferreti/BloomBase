<laravel-boost-guidelines>
=== .ai/laravel-action-pattern rules ===

# Laravel Action Pattern

## When to Apply

Activate this guideline when:

- Creating or modifying action classes (single-responsibility classes in `App\Actions`)
- Decomposing controllers or models that violate Single Responsibility Principle
- Extracting reusable business logic from controllers, jobs, listeners, or commands
- Writing testable, decoupled business operations that may be used from multiple entry points

## What Is the Action Pattern

The Action Pattern encapsulates a single business operation into its own dedicated class. Each action class has a single public `handle()` method and follows the Single Responsibility Principle. Actions are testable in isolation and reusable across controllers, commands, queues, and other callers.

## Directory Convention

Place action classes in `app/Actions/{Domain}/`:

```
app/Actions/
├── Auth/
│   ├── RegisterUser.php
│   └── ResetUserPassword.php
├── Shop/
│   ├── AddProductToCart.php
│   ├── CalculateCartTotal.php
│   └── CheckoutCart.php
└── User/
    ├── SyncUserRoles.php
    └── UpdateUserProfile.php
```

## Creating Action Classes

Generate action classes with `php artisan make:class`:

```bash
php artisan make:class Actions/Shop/CheckoutCart
```

Alternatively, create them manually following the template below.

## Action Class Structure

### Basic Action

```php
namespace App\Actions\Shop;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CheckoutCart
{
    public function handle(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $cart = $user->cart()->with('items.product')->firstOrFail();

            $order = $user->orders()->create([
                'total' => $cart->total(),
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            $cart->items()->delete();

            return $order;
        });
    }
}
```

## Using Actions

Actions are always invoked inline — never assign an action to a variable. Use `app(MyAction::class)->handle()` for classes resolved through the container, or `new MyAction()->handle()` when no container resolution is needed.

```php
// ✅ Inline invocation (preferred)
app(\App\Actions\Shop\CheckoutCart::class)->handle($user, $data);

// ✅ With constructor parameters via new
new \App\Actions\Auth\RegisterUser()->handle($data);

// ❌ Never assign to a variable
$action = app(\App\Actions\Shop\CheckoutCart::class);
$action->handle($user, $data);
```

This pattern works everywhere — controllers, commands, jobs, listeners, and tests:

```php
// In a controller
public function store(CheckoutRequest $request)
{
    $order = app(CheckoutCart::class)->handle(
        $request->user(),
        $request->validated()
    );

    return redirect()->route('orders.show', $order);
}

// In a command
public function handle(): void
{
    $user = User::findOrFail($this->argument('user'));

    app(SyncUserRoles::class)->handle($user, [$this->argument('role')]);
}
```

## Using Actions in Tests

Actions are easily testable in isolation:

```php
use App\Actions\Auth\RegisterUser;
use App\Models\User;

it('registers a new user', function () {
    $user = app(RegisterUser::class)->handle([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    expect($user)
        ->toBeInstanceOf(User::class)
        ->and($user->email)->toBe('john@example.com')
        ->and(Hash::check('Password123!', $user->password))->toBeTrue();
});
```

## When NOT to Use Actions

- **Simple CRUD operations** — if the logic is a single Eloquent call with no business rules, keep it in the controller or use a Form Request
- **Query Scopes** — use Eloquent local/global scopes instead
- **Already-simple operations** — don't create an action class for `User::find($id)`; the pattern adds value when there's meaningful logic to encapsulate

## Best Practices

1. **One action, one responsibility** — if an action does more than one thing, split it
2. **Use constructor injection** for dependencies (repositories, services, other actions)
3. **Invoke inline, never assign** — use `app(MyAction::class)->handle()` or `new MyAction()->handle()` directly; never assign the action to a variable
4. **Return typed results** — always declare a return type on `handle()`
5. **Database transactions** inside the action when multiple writes are involved
6. **Validation inside the action** only when it's intrinsic to the business operation; prefer Form Requests for HTTP-specific validation
7. **Test actions in isolation** — they are the most testable unit of business logic
8. **Name clearly** — `PurchaseSubscription`, `ArchiveInactiveUsers`, not `ProcessSomething`
9. **Don't over-abstract** — reach for this pattern when logic is reused, complex, or needs isolated testing

=== .ai/pest-testing rules ===

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

=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.5
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v4
- larastan/larastan (LARASTAN) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- rector/rector (RECTOR) - v2
- tailwindcss (TAILWINDCSS) - v4
- prettier (PRETTIER) - v3

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

</laravel-boost-guidelines>
