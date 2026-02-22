---
title: Introduction
weight: 1
---

PHP 8.0 introduced attributes — a way to add structured metadata to classes, methods, properties, constants, parameters, and functions. The idea is great, but the reflection API to read them is verbose and awkward.

Imagine you want to check if a class has a specific attribute, and grab the attribute instance. Here's what native PHP requires:

```php
$reflection = new ReflectionClass(MyController::class);
$attributes = $reflection->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

$route = null;
if (count($attributes) > 0) {
    $route = $attributes[0]->newInstance();
}
```

Now imagine you want to find every `#[Validate]` attribute across a class — on the class itself, its methods, properties, constants, and method parameters. Here's what that looks like:

```php
$reflection = new ReflectionClass(MyForm::class);
$results = [];

// Check the class itself
foreach ($reflection->getAttributes(Validate::class, ReflectionAttribute::IS_INSTANCEOF) as $attr) {
    $results[] = [
        'attribute' => $attr->newInstance(),
        'target' => $reflection,
        'name' => $reflection->getName(),
    ];
}

// Check every method
foreach ($reflection->getMethods() as $method) {
    foreach ($method->getAttributes(Validate::class, ReflectionAttribute::IS_INSTANCEOF) as $attr) {
        $results[] = [
            'attribute' => $attr->newInstance(),
            'target' => $method,
            'name' => $method->getName(),
        ];
    }

    // Check every parameter on every method
    foreach ($method->getParameters() as $parameter) {
        foreach ($parameter->getAttributes(Validate::class, ReflectionAttribute::IS_INSTANCEOF) as $attr) {
            $results[] = [
                'attribute' => $attr->newInstance(),
                'target' => $parameter,
                'name' => $method->getName() . '.' . $parameter->getName(),
            ];
        }
    }
}

// Check every property
foreach ($reflection->getProperties() as $property) {
    foreach ($property->getAttributes(Validate::class, ReflectionAttribute::IS_INSTANCEOF) as $attr) {
        $results[] = [
            'attribute' => $attr->newInstance(),
            'target' => $property,
            'name' => $property->getName(),
        ];
    }
}

// Check every constant
foreach ($reflection->getReflectionConstants() as $constant) {
    foreach ($constant->getAttributes(Validate::class, ReflectionAttribute::IS_INSTANCEOF) as $attr) {
        $results[] = [
            'attribute' => $attr->newInstance(),
            'target' => $constant,
            'name' => $constant->getName(),
        ];
    }
}
```

That's 40+ lines of nested loops just to answer "where is this attribute used?". And you'll write variations of this over and over.

This package replaces all of that with a clean, static API:

```php
use Spatie\Attributes\Attributes;

// The 4-line class check becomes:
$route = Attributes::get(MyController::class, Route::class);

// The 40-line discovery nightmare becomes:
$results = Attributes::find(MyForm::class, Validate::class);
```

Every method returns instantiated attribute objects — no `->newInstance()` calls. Missing targets return `null` instead of throwing exceptions. Child attributes are matched automatically via `IS_INSTANCEOF`.

## We got badges

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/php-attribute-reader.svg?style=flat-square)](https://packagist.org/packages/spatie/php-attribute-reader)
[![Tests](https://github.com/spatie/php-attribute-reader/actions/workflows/run-tests-pest.yml/badge.svg)](https://github.com/spatie/php-attribute-reader/actions/workflows/run-tests-pest.yml)
