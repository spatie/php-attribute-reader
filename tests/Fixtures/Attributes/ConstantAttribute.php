<?php

namespace Spatie\Attributes\Tests\Fixtures\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class ConstantAttribute
{
    public function __construct(
        public string $description = '',
    ) {}
}
