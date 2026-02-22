---
title: Discovering attributes
weight: 3
---

The `find()` method searches an entire class for usages of an attribute: on the class itself, all methods, properties, constants, and method parameters.

## Basic usage

```php
use Spatie\Attributes\Attributes;

#[Attribute(Attribute::TARGET_ALL)]
class Validate
{
    public function __construct(public string $rule = 'required') {}
}

#[Validate('exists:forms')]
class ContactForm
{
    #[Validate('string|max:255')]
    public string $name;

    #[Validate('email')]
    public string $email;

    public function submit(#[Validate('array')] array $data) {}
}

$results = Attributes::find(ContactForm::class, Validate::class);

count($results); // 4
```

## The AttributeTarget object

Each result is an `AttributeTarget` instance with three properties:

```php
use Spatie\Attributes\AttributeTarget;

foreach ($results as $result) {
    $result->attribute; // The instantiated attribute (e.g. Validate instance)
    $result->target;    // The Reflection object (ReflectionClass, ReflectionMethod, etc.)
    $result->name;      // A human-readable name (e.g. 'name', 'submit', 'submit.data')
}
```

For the example above, the results would be:

| `->name` | `->target` type | `->attribute->rule` |
|---|---|---|
| `ContactForm` | `ReflectionClass` | `exists:forms` |
| `name` | `ReflectionProperty` | `string\|max:255` |
| `email` | `ReflectionProperty` | `email` |
| `submit.data` | `ReflectionParameter` | `array` |

For parameters, the name is formatted as `method.parameter`.

## Using the reflection target

The `target` property gives you direct access to the underlying reflection object, so you can inspect additional details:

```php
foreach (Attributes::find(ContactForm::class, Validate::class) as $result) {
    if ($result->target instanceof ReflectionProperty) {
        echo $result->target->getType(); // e.g. 'string'
    }
}
```

## Finding all attributes

You can call `find()` without an attribute filter to get every attribute on a class:

```php
$results = Attributes::find(ContactForm::class);

// Returns all attributes across the class, methods, properties, constants, and parameters
```
