---
title: Reading class attributes
weight: 1
---

All examples on this page use the following attribute and class:

```php
#[Attribute]
class Description
{
    public function __construct(
        public string $text,
    ) {}
}

#[Description('A user account')]
class User {}
```

## Getting an attribute

Use `get()` to retrieve a single attribute instance from a class. Returns `null` if the attribute is not present.

```php
use Spatie\Attributes\Attributes;

$description = Attributes::get(User::class, Description::class);

$description->text; // 'A user account'
```

## Checking for an attribute

Use `has()` to check if a class has a specific attribute:

```php
Attributes::has(User::class, Description::class); // true
Attributes::has(User::class, SomeOtherAttribute::class); // false
```

## Repeated attributes

If an attribute is marked as `IS_REPEATABLE`, use `getAll()` to retrieve all instances. Returns an empty array when no matching attributes exist.

```php
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Tag
{
    public function __construct(public string $name) {}
}

#[Tag('featured')]
#[Tag('popular')]
class Post {}

$tags = Attributes::getAll(Post::class, Tag::class);

$tags[0]->name; // 'featured'
$tags[1]->name; // 'popular'
```

## Using object instances

All methods accept either a class string or an object instance:

```php
$user = new User();

Attributes::get($user, Description::class);
Attributes::has($user, Description::class);
```
