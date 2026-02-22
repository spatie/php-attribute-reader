<?php

namespace Spatie\Attributes\Tests\TestSupport\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ChildAttribute extends SimpleAttribute {}
