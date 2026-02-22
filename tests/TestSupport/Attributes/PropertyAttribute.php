<?php

namespace Spatie\Attributes\Tests\TestSupport\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyAttribute
{
    public function __construct(
        public bool $fillable = true,
    ) {}
}
