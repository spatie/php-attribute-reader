<?php

namespace Spatie\Attributes\Tests\TestSupport;

use Spatie\Attributes\Tests\TestSupport\Attributes\VariadicAttribute;

#[VariadicAttribute('featured', 'popular', 'trending')]
class VariadicClass {}
