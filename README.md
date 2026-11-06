<div align="left">

<h1>A clean API for working with PHP attributes</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/php-attribute-reader.svg?style=flat-square)](https://packagist.org/packages/spatie/php-attribute-reader)
[![GitHub Tests Action Status](https://github.com/spatie/php-attribute-reader/actions/workflows/run-tests-pest.yml/badge.svg)](https://github.com/spatie/php-attribute-reader/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://github.com/spatie/php-attribute-reader/actions/workflows/fix-php-code-style-issues-pint.yml/badge.svg)](https://github.com/spatie/php-attribute-reader/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/php-attribute-reader.svg?style=flat-square)](https://packagist.org/packages/spatie/php-attribute-reader)

</div>

PHP 8.0 introduced attributes, but the reflection API to actually read them is verbose and awkward. Want to check if a class has a specific attribute? That's four lines. Want to find every occurrence of an attribute across a class, its methods, properties, constants, and parameters? That's 40+ lines of nested foreach loops.

This package gives you a clean, static API instead:

```php
use Spatie\Attributes\Attributes;

// Get a single attribute from a class
$route = Attributes::get(MyController::class, Route::class);

// Check if a class has an attribute
Attributes::has(MyController::class, Route::class); // true

// Read from methods, properties, constants, parameters
Attributes::onMethod(MyController::class, 'index', Route::class);
Attributes::onProperty(User::class, 'email', Column::class);
Attributes::onConstant(Status::class, 'ACTIVE', Label::class);
Attributes::onParameter(MyController::class, 'show', 'id', FromRoute::class);

// Find an attribute everywhere in a class at once
$results = Attributes::find(MyForm::class, Validate::class);

foreach ($results as $result) {
    $result->attribute; // The instantiated attribute
    $result->target;    // The Reflection object
    $result->name;      // e.g. 'email', 'handle.request'
}
```

Every method returns instantiated attribute objects, missing targets return `null` instead of throwing exceptions, and child attributes are matched automatically.

## Support us

[<img src="https://spatie.be/github-ad-image/php-attribute-reader.jpg" width="419px" />](https://spatie.be/github-ad-click/php-attribute-reader)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Documentation

All documentation is available [on our documentation site](https://spatie.be/docs/php-attribute-reader).

## Testing

PHP 8.4 or higher is required. The test suite uses Pest 5.

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
