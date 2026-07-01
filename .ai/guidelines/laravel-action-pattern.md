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
