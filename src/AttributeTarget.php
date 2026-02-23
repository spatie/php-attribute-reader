<?php

namespace Spatie\Attributes;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class AttributeTarget
{
    public function __construct(
        public object $attribute,
        public ReflectionClass|ReflectionMethod|ReflectionProperty|ReflectionClassConstant|ReflectionParameter|ReflectionFunction $target,
        public string $name,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return get_object_vars($this->attribute);
    }
}
