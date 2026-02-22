<?php

namespace Spatie\Attributes\Tests\TestSupport\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class RepeatableTag
{
    public function __construct(
        public string $tag = '',
    ) {}
}
