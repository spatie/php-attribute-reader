<?php

namespace Spatie\Attributes\Tests\Fixtures\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class ParameterAttribute
{
    public function __construct(
        public string $type = 'string',
    ) {}
}
