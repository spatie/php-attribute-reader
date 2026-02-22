---
title: Attribute inheritance
weight: 4
---

All methods in this package use `ReflectionAttribute::IS_INSTANCEOF` by default. This means child attributes match when you query for a parent.

## Example

```php
#[Attribute(Attribute::TARGET_CLASS)]
class CacheStrategy
{
    public function __construct(public int $ttl = 3600) {}
}

#[Attribute(Attribute::TARGET_CLASS)]
class AggressiveCache extends CacheStrategy
{
    public function __construct()
    {
        parent::__construct(ttl: 86400);
    }
}

#[AggressiveCache]
class ProductCatalog {}
```

Querying for the parent `CacheStrategy` will find the child `AggressiveCache`:

```php
use Spatie\Attributes\Attributes;

$cache = Attributes::get(ProductCatalog::class, CacheStrategy::class);

$cache instanceof AggressiveCache; // true
$cache->ttl; // 86400
```

Querying for the exact child class works too:

```php
$cache = Attributes::get(ProductCatalog::class, AggressiveCache::class);

$cache->ttl; // 86400
```

This applies to all methods: `get()`, `has()`, `getAll()`, `onMethod()`, `onProperty()`, `onConstant()`, `onParameter()`, `onFunction()`, and `find()`.
