---
title: Reading from specific targets
weight: 2
---

Beyond class-level attributes, you can read attributes from methods, properties, constants, parameters, and standalone functions.

All examples on this page use the following setup:

```php
use Spatie\Attributes\Attributes;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(public string $path) {}
}

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column
{
    public function __construct(public string $name) {}
}

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Label
{
    public function __construct(public string $text) {}
}

#[Attribute(Attribute::TARGET_PARAMETER)]
class FromQuery
{
    public function __construct(public string $key = '') {}
}

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Middleware
{
    public function __construct(public string $name) {}
}

class UserController
{
    #[Label('Active')]
    public const STATUS_ACTIVE = 'active';

    #[Column('email_address')]
    public string $email;

    #[Route('/users')]
    #[Middleware('auth')]
    #[Middleware('verified')]
    public function index(#[FromQuery('q')] string $query) {}
}
```

## Methods

```php
$route = Attributes::onMethod(UserController::class, 'index', Route::class);

$route->path; // '/users'
```

## Properties

```php
$column = Attributes::onProperty(UserController::class, 'email', Column::class);

$column->name; // 'email_address'
```

## Constants

```php
$label = Attributes::onConstant(UserController::class, 'STATUS_ACTIVE', Label::class);

$label->text; // 'Active'
```

## Parameters

Specify both the method name and parameter name:

```php
$fromQuery = Attributes::onParameter(UserController::class, 'index', 'query', FromQuery::class);

$fromQuery->key; // 'q'
```

## Functions

For standalone functions, use `onFunction()` with the fully qualified function name:

```php
#[Attribute]
class Deprecated
{
    public function __construct(public string $reason = '') {}
}

#[Deprecated('Use newHelper() instead')]
function oldHelper() {}

$deprecated = Attributes::onFunction('oldHelper', Deprecated::class);

$deprecated->reason; // 'Use newHelper() instead'
```

## Repeated attributes

When an attribute is repeatable, use the `getAllOn*` methods to retrieve all instances:

```php
$middlewares = Attributes::getAllOnMethod(UserController::class, 'index', Middleware::class);

$middlewares[0]->name; // 'auth'
$middlewares[1]->name; // 'verified'
```

The same pattern is available for all target types:

```php
Attributes::getAllOnMethod($class, $method, $attribute);     // array
Attributes::getAllOnProperty($class, $property, $attribute);  // array
Attributes::getAllOnConstant($class, $constant, $attribute);  // array
Attributes::getAllOnParameter($class, $method, $parameter, $attribute); // array
```

## Missing targets

The `on*` methods return `null` when the target doesn't exist or lacks the attribute. The `getAllOn*` methods return an empty array. No exceptions are thrown.

```php
Attributes::onMethod(UserController::class, 'nonExistent', Route::class); // null
Attributes::getAllOnMethod(UserController::class, 'nonExistent', Middleware::class); // []
```
