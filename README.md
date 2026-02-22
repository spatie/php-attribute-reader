# A clean API for working with PHP attributes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/php-attribute-reader.svg?style=flat-square)](https://packagist.org/packages/spatie/php-attribute-reader)
[![Tests](https://github.com/spatie/php-attribute-reader/actions/workflows/run-tests-pest.yml/badge.svg)](https://github.com/spatie/php-attribute-reader/actions/workflows/run-tests-pest.yml)

PHP's native attribute reflection API is verbose. There's no `hasAttribute()`, no `getAttribute()` returning a single instance, no way to search all class members without nested foreach loops. This package fills the gap.

## Installation

```bash
composer require spatie/php-attribute-reader
```

## Usage

```php
use Spatie\Attributes\Attributes;

// Class-level
Attributes::get(MyClass::class, MyAttribute::class);      // ?MyAttribute
Attributes::has(MyClass::class, MyAttribute::class);       // bool
Attributes::getAll(MyClass::class, MyAttribute::class);    // array<MyAttribute>

// Specific targets
Attributes::onMethod(MyClass::class, 'handle', MyAttribute::class);
Attributes::onProperty(MyClass::class, 'name', MyAttribute::class);
Attributes::onConstant(MyClass::class, 'STATUS', MyAttribute::class);
Attributes::onParameter(MyClass::class, 'handle', 'request', MyAttribute::class);
Attributes::onFunction('myFunction', MyAttribute::class);

// Discovery: find everywhere in a class
$results = Attributes::find(MyClass::class, MyAttribute::class); // array<AttributeTarget>
// Each result: ->attribute, ->target (Reflection*), ->name (string)

// All methods accept objects too
Attributes::get($object, MyAttribute::class);
```

Key behaviors:
- Uses `IS_INSTANCEOF` by default (child attributes match parent queries)
- Always returns instantiated attributes (no `->newInstance()` needed)
- Returns `null` for missing targets (no exceptions)
- PHPDoc `@template` generics for IDE type inference

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
