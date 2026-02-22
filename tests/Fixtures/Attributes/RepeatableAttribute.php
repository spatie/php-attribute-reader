<?php

namespace Spatie\Attributes\Tests\Fixtures\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class RepeatableAttribute
{
    public function __construct(
        public string $tag = '',
    ) {}
}
