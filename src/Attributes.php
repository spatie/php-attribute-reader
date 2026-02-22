<?php

namespace Spatie\Attributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionFunction;

class Attributes
{
    /**
     * @template T of object
     *
     * @param  class-string|object  $class
     * @param  class-string<T>  $attribute
     * @return T|null
     */
    public static function get(string|object $class, string $attribute): ?object
    {
        return self::firstFrom(self::reflect($class)->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF));
    }

    /**
     * @param  class-string|object  $class
     * @param  class-string  $attribute
     */
    public static function has(string|object $class, string $attribute): bool
    {
        return self::reflect($class)->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF) !== [];
    }

    /**
     * @template T of object
     *
     * @param  class-string|object  $class
     * @param  class-string<T>  $attribute
     * @return array<T>
     */
    public static function getAll(string|object $class, string $attribute): array
    {
        return self::instantiateAll(self::reflect($class)->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF));
    }

    /**
     * @template T of object
     *
     * @param  class-string|object  $class
     * @param  class-string<T>  $attribute
     * @return T|null
     */
    public static function onMethod(string|object $class, string $method, string $attribute): ?object
    {
        $reflection = self::reflect($class);

        if (! $reflection->hasMethod($method)) {
            return null;
        }

        return self::firstFrom($reflection->getMethod($method)->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF));
    }

    /**
     * @template T of object
     *
     * @param  class-string|object  $class
     * @param  class-string<T>  $attribute
     * @return T|null
     */
    public static function onProperty(string|object $class, string $property, string $attribute): ?object
    {
        $reflection = self::reflect($class);

        if (! $reflection->hasProperty($property)) {
            return null;
        }

        return self::firstFrom($reflection->getProperty($property)->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF));
    }

    /**
     * @template T of object
     *
     * @param  class-string|object  $class
     * @param  class-string<T>  $attribute
     * @return T|null
     */
    public static function onConstant(string|object $class, string $constant, string $attribute): ?object
    {
        $reflection = self::reflect($class);

        if (! $reflection->hasConstant($constant)) {
            return null;
        }

        return self::firstFrom($reflection->getReflectionConstant($constant)->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF));
    }

    /**
     * @template T of object
     *
     * @param  class-string|object  $class
     * @param  class-string<T>  $attribute
     * @return T|null
     */
    public static function onParameter(string|object $class, string $method, string $parameter, string $attribute): ?object
    {
        $reflection = self::reflect($class);

        if (! $reflection->hasMethod($method)) {
            return null;
        }

        return self::firstFrom(self::findParameter($reflection->getMethod($method), $parameter)?->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF) ?? []);
    }

    /**
     * @template T of object
     *
     * @param  class-string<T>  $attribute
     * @return T|null
     */
    public static function onFunction(string $function, string $attribute): ?object
    {
        return self::firstFrom((new ReflectionFunction($function))->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF));
    }

    /**
     * @template T of object
     *
     * @param  class-string|object  $class
     * @param  class-string<T>|null  $attribute
     * @return array<AttributeTarget>
     */
    public static function find(string|object $class, ?string $attribute = null): array
    {
        $reflection = self::reflect($class);
        $results = [];
        $args = $attribute !== null ? [$attribute, ReflectionAttribute::IS_INSTANCEOF] : [];

        foreach ($reflection->getAttributes(...$args) as $attr) {
            $results[] = new AttributeTarget($attr->newInstance(), $reflection, $reflection->getName());
        }

        foreach ($reflection->getMethods() as $method) {
            foreach ($method->getAttributes(...$args) as $attr) {
                $results[] = new AttributeTarget($attr->newInstance(), $method, $method->getName());
            }

            foreach ($method->getParameters() as $parameter) {
                foreach ($parameter->getAttributes(...$args) as $attr) {
                    $results[] = new AttributeTarget($attr->newInstance(), $parameter, $method->getName().'.'.$parameter->getName());
                }
            }
        }

        foreach ($reflection->getProperties() as $property) {
            foreach ($property->getAttributes(...$args) as $attr) {
                $results[] = new AttributeTarget($attr->newInstance(), $property, $property->getName());
            }
        }

        foreach ($reflection->getReflectionConstants() as $constant) {
            foreach ($constant->getAttributes(...$args) as $attr) {
                $results[] = new AttributeTarget($attr->newInstance(), $constant, $constant->getName());
            }
        }

        return $results;
    }

    private static function reflect(string|object $class): ReflectionClass
    {
        return new ReflectionClass($class);
    }

    /**
     * @param  array<ReflectionAttribute>  $attributes
     */
    private static function firstFrom(array $attributes): ?object
    {
        if ($attributes === []) {
            return null;
        }

        return $attributes[0]->newInstance();
    }

    /**
     * @param  array<ReflectionAttribute>  $attributes
     * @return array<object>
     */
    private static function instantiateAll(array $attributes): array
    {
        return array_map(fn (ReflectionAttribute $attr) => $attr->newInstance(), $attributes);
    }

    private static function findParameter(\ReflectionMethod $method, string $name): ?\ReflectionParameter
    {
        foreach ($method->getParameters() as $parameter) {
            if ($parameter->getName() === $name) {
                return $parameter;
            }
        }

        return null;
    }
}
