<?php

namespace Spatie\Attributes\Tests\TestSupport;

use Spatie\Attributes\Tests\TestSupport\Attributes\MultiTargetAttribute;

#[MultiTargetAttribute(label: 'standalone')]
function testFunction(): void {}
