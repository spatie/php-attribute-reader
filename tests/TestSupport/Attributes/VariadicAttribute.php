<?php

namespace Spatie\Attributes\Tests\TestSupport\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class VariadicAttribute
{
    public array $tags;

    public function __construct(string ...$tags)
    {
        $this->tags = $tags;
    }
}
