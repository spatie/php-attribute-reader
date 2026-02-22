<?php

namespace Spatie\Attributes\Tests\TestSupport;

use Spatie\Attributes\Tests\TestSupport\Attributes\ChildAttribute;

#[ChildAttribute(name: 'child-class')]
class ChildTestClass {}
