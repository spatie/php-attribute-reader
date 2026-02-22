<?php

namespace Spatie\Attributes\Tests\Fixtures;

use Spatie\Attributes\Tests\Fixtures\Attributes\MultiTargetAttribute;

#[MultiTargetAttribute(label: 'standalone')]
function testFunction(): void {}
