---
title: Reading from specific targets
weight: 2
---

Beyond class-level attributes, you can read attributes from methods, properties, constants, parameters, and standalone functions.

## Methods

```php
use Spatie\Attributes\Attributes;

#[Attribute]
class Route
{
    public function __construct(public string $path) {}
}

class UserController
{
    #[Route('/users')]
    public function index() {}
}

$route = Attributes::onMethod(UserController::class, 'index', Route::class);

$route->path; // '/users'
```

Returns `null` if the method doesn't exist or doesn't have the attribute.

## Properties

```php
#[Attribute]
class Column
{
    public function __construct(public string $name) {}
}

class User
{
    #[Column('email_address')]
    public string $email;
}

$column = Attributes::onProperty(User::class, 'email', Column::class);

$column->name; // 'email_address'
```

## Constants

```php
#[Attribute]
class Label
{
    public function __construct(public string $text) {}
}

class Status
{
    #[Label('Active')]
    public const ACTIVE = 'active';
}

$label = Attributes::onConstant(Status::class, 'ACTIVE', Label::class);

$label->text; // 'Active'
```

## Parameters

Specify the method name and parameter name:

```php
#[Attribute]
class FromQuery
{
    public function __construct(public string $key = '') {}
}

class SearchController
{
    public function search(#[FromQuery('q')] string $query) {}
}

$fromQuery = Attributes::onParameter(SearchController::class, 'search', 'query', FromQuery::class);

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

## Null safety

All methods return `null` when the target doesn't exist or doesn't have the attribute. No exceptions are thrown.

```php
Attributes::onMethod(User::class, 'nonExistent', Route::class); // null
Attributes::onProperty(User::class, 'nonExistent', Column::class); // null
Attributes::onConstant(User::class, 'NON_EXISTENT', Label::class); // null
Attributes::onParameter(User::class, 'nonExistent', 'param', FromQuery::class); // null
```
