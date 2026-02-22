---
title: Introduction
weight: 1
---

PHP 8.0 introduced attributes, a way to add structured metadata to classes, methods, properties, constants, parameters, and functions. The idea is great, but the reflection API to read them is verbose.

Imagine you have a controller with a `Route` attribute:

```php
#[Route('/my-controller')]
class MyController
{
    // ...
}
```

Getting that attribute instance using native PHP requires this:

```php
$reflection = new ReflectionClass(MyController::class);
$attributes = $reflection->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

if (count($attributes) > 0) {
    $route = $attributes[0]->newInstance();
}
```

With this package, it becomes a single call:

```php
use Spatie\Attributes\Attributes;

$route = Attributes::get(MyController::class, Route::class);
```

The package handles instantiation, `IS_INSTANCEOF` matching, and returns `null` for missing targets instead of throwing exceptions.

It works on all attribute targets: classes, methods, properties, constants, parameters, and functions. It supports repeatable attributes and can discover all usages of an attribute across an entire class.

## We got badges

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/php-attribute-reader.svg?style=flat-square)](https://packagist.org/packages/spatie/php-attribute-reader)
[![Tests](https://github.com/spatie/php-attribute-reader/actions/workflows/run-tests-pest.yml/badge.svg)](https://github.com/spatie/php-attribute-reader/actions/workflows/run-tests-pest.yml)
