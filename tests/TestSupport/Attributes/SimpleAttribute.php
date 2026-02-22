<?php

namespace Spatie\Attributes\Tests\TestSupport\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SimpleAttribute
{
    public function __construct(
        public string $name = 'default',
    ) {}
}
