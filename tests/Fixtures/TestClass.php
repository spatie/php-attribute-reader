<?php

namespace Spatie\Attributes\Tests\Fixtures;

use Spatie\Attributes\Tests\Fixtures\Attributes\ConstantAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\MethodAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\MultiTargetAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\ParameterAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\PropertyAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\RepeatableAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\SimpleAttribute;

#[SimpleAttribute(name: 'test-class')]
#[RepeatableAttribute(tag: 'first')]
#[RepeatableAttribute(tag: 'second')]
class TestClass
{
    #[ConstantAttribute(description: 'The active status')]
    public const STATUS_ACTIVE = 'active';

    #[PropertyAttribute(fillable: true)]
    public string $name = '';

    #[PropertyAttribute(fillable: false)]
    public string $secret = '';

    #[MethodAttribute(route: '/handle')]
    public function handle(
        #[ParameterAttribute(type: 'request')] $request,
        #[ParameterAttribute(type: 'int')] $id,
    ): void {}

    #[MethodAttribute(route: '/process')]
    #[MultiTargetAttribute(label: 'processor')]
    public function process(): void {}

    public function plain(): void {}
}
