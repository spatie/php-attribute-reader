<?php

namespace Spatie\Attributes\Tests\TestSupport\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class MethodAttribute
{
    public function __construct(
        public string $route = '/',
    ) {}
}
