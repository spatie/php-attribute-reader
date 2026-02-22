<?php

namespace Spatie\Attributes\Tests\TestSupport;

use Spatie\Attributes\Tests\TestSupport\Attributes\ConstantAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\MethodAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\MultiTargetAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\ParameterAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\PropertyAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\RepeatableAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\RepeatableTag;
use Spatie\Attributes\Tests\TestSupport\Attributes\SimpleAttribute;

#[SimpleAttribute(name: 'test-class')]
#[RepeatableAttribute(tag: 'first')]
#[RepeatableAttribute(tag: 'second')]
class TestClass
{
    #[ConstantAttribute(description: 'The active status')]
    #[RepeatableTag(tag: 'const-a')]
    #[RepeatableTag(tag: 'const-b')]
    public const STATUS_ACTIVE = 'active';

    #[PropertyAttribute(fillable: true)]
    #[RepeatableTag(tag: 'prop-a')]
    #[RepeatableTag(tag: 'prop-b')]
    public string $name = '';

    #[PropertyAttribute(fillable: false)]
    public string $secret = '';

    #[MethodAttribute(route: '/handle')]
    #[RepeatableTag(tag: 'method-a')]
    #[RepeatableTag(tag: 'method-b')]
    public function handle(
        #[ParameterAttribute(type: 'request')]
        #[RepeatableTag(tag: 'param-a')]
        #[RepeatableTag(tag: 'param-b')]
        $request,
        #[ParameterAttribute(type: 'int')] $id,
    ): void {}

    #[MethodAttribute(route: '/process')]
    #[MultiTargetAttribute(label: 'processor')]
    public function process(): void {}

    public function plain(): void {}
}
