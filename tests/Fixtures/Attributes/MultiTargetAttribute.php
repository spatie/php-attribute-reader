<?php

namespace Spatie\Attributes\Tests\Fixtures\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_ALL)]
class MultiTargetAttribute
{
    public function __construct(
        public string $label = '',
    ) {}
}
