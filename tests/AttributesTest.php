<?php

use Spatie\Attributes\Attributes;
use Spatie\Attributes\AttributeTarget;
use Spatie\Attributes\Tests\TestSupport\Attributes\ChildAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\ConstantAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\MethodAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\MultiTargetAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\ParameterAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\PropertyAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\RepeatableAttribute;
use Spatie\Attributes\Tests\TestSupport\Attributes\RepeatableTag;
use Spatie\Attributes\Tests\TestSupport\Attributes\SimpleAttribute;
use Spatie\Attributes\Tests\TestSupport\ChildTestClass;
use Spatie\Attributes\Tests\TestSupport\PlainClass;
use Spatie\Attributes\Tests\TestSupport\TestClass;

// get

it('can get a class attribute', function () {
    $attribute = Attributes::get(TestClass::class, SimpleAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(SimpleAttribute::class)
        ->name->toBe('test-class');
});

it('returns null when class attribute is missing', function () {
    expect(Attributes::get(PlainClass::class, SimpleAttribute::class))->toBeNull();
});

it('can get the first class attribute without filtering', function () {
    $attribute = Attributes::get(TestClass::class);

    expect($attribute)->toBeInstanceOf(SimpleAttribute::class);
});

it('returns null for get without filter on plain class', function () {
    expect(Attributes::get(PlainClass::class))->toBeNull();
});

it('can get a class attribute from an object instance', function () {
    $attribute = Attributes::get(new TestClass, SimpleAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(SimpleAttribute::class)
        ->name->toBe('test-class');
});

// has

it('can check if a class has an attribute', function () {
    expect(Attributes::has(TestClass::class, SimpleAttribute::class))->toBeTrue();
    expect(Attributes::has(PlainClass::class, SimpleAttribute::class))->toBeFalse();
});

it('can check if a class has any attribute', function () {
    expect(Attributes::has(TestClass::class))->toBeTrue();
    expect(Attributes::has(PlainClass::class))->toBeFalse();
});

it('has returns true for child attributes via inheritance', function () {
    expect(Attributes::has(ChildTestClass::class, SimpleAttribute::class))->toBeTrue();
});

// getAll

it('can get all repeated attributes', function () {
    $attributes = Attributes::getAll(TestClass::class, RepeatableAttribute::class);

    expect($attributes)
        ->toHaveCount(2)
        ->sequence(
            fn ($attr) => $attr->tag->toBe('first'),
            fn ($attr) => $attr->tag->toBe('second'),
        );
});

it('returns empty array when no repeated attributes exist', function () {
    expect(Attributes::getAll(PlainClass::class, RepeatableAttribute::class))->toBeEmpty();
});

it('can get all class attributes without filtering', function () {
    $attributes = Attributes::getAll(TestClass::class);

    expect($attributes)->toHaveCount(3);
});

it('returns empty array for getAll without filter on plain class', function () {
    expect(Attributes::getAll(PlainClass::class))->toBeEmpty();
});

// getAllOnMethod

it('can get all repeated attributes from a method', function () {
    $attributes = Attributes::getAllOnMethod(TestClass::class, 'handle', RepeatableTag::class);

    expect($attributes)
        ->toHaveCount(2)
        ->sequence(
            fn ($attr) => $attr->tag->toBe('method-a'),
            fn ($attr) => $attr->tag->toBe('method-b'),
        );
});

it('returns empty array for getAllOnMethod with non-existent method', function () {
    expect(Attributes::getAllOnMethod(TestClass::class, 'nonExistent', RepeatableTag::class))->toBeEmpty();
});

// getAllOnProperty

it('can get all repeated attributes from a property', function () {
    $attributes = Attributes::getAllOnProperty(TestClass::class, 'name', RepeatableTag::class);

    expect($attributes)
        ->toHaveCount(2)
        ->sequence(
            fn ($attr) => $attr->tag->toBe('prop-a'),
            fn ($attr) => $attr->tag->toBe('prop-b'),
        );
});

it('returns empty array for getAllOnProperty with non-existent property', function () {
    expect(Attributes::getAllOnProperty(TestClass::class, 'nonExistent', RepeatableTag::class))->toBeEmpty();
});

// getAllOnConstant

it('can get all repeated attributes from a constant', function () {
    $attributes = Attributes::getAllOnConstant(TestClass::class, 'STATUS_ACTIVE', RepeatableTag::class);

    expect($attributes)
        ->toHaveCount(2)
        ->sequence(
            fn ($attr) => $attr->tag->toBe('const-a'),
            fn ($attr) => $attr->tag->toBe('const-b'),
        );
});

it('returns empty array for getAllOnConstant with non-existent constant', function () {
    expect(Attributes::getAllOnConstant(TestClass::class, 'NON_EXISTENT', RepeatableTag::class))->toBeEmpty();
});

// getAllOnParameter

it('can get all repeated attributes from a parameter', function () {
    $attributes = Attributes::getAllOnParameter(TestClass::class, 'handle', 'request', RepeatableTag::class);

    expect($attributes)
        ->toHaveCount(2)
        ->sequence(
            fn ($attr) => $attr->tag->toBe('param-a'),
            fn ($attr) => $attr->tag->toBe('param-b'),
        );
});

it('returns empty array for getAllOnParameter with non-existent method', function () {
    expect(Attributes::getAllOnParameter(TestClass::class, 'nonExistent', 'request', RepeatableTag::class))->toBeEmpty();
});

it('returns empty array for getAllOnParameter with non-existent parameter', function () {
    expect(Attributes::getAllOnParameter(TestClass::class, 'handle', 'nonExistent', RepeatableTag::class))->toBeEmpty();
});

// getAllOnMethod without filter

it('can get all attributes from a method without filtering', function () {
    $attributes = Attributes::getAllOnMethod(TestClass::class, 'handle');

    expect($attributes)->toHaveCount(3);
});

// getAllOnProperty without filter

it('can get all attributes from a property without filtering', function () {
    $attributes = Attributes::getAllOnProperty(TestClass::class, 'name');

    expect($attributes)->toHaveCount(3);
});

// getAllOnConstant without filter

it('can get all attributes from a constant without filtering', function () {
    $attributes = Attributes::getAllOnConstant(TestClass::class, 'STATUS_ACTIVE');

    expect($attributes)->toHaveCount(3);
});

// getAllOnParameter without filter

it('can get all attributes from a parameter without filtering', function () {
    $attributes = Attributes::getAllOnParameter(TestClass::class, 'handle', 'request');

    expect($attributes)->toHaveCount(3);
});

// onMethod

it('can get an attribute from a method', function () {
    $attribute = Attributes::onMethod(TestClass::class, 'handle', MethodAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(MethodAttribute::class)
        ->route->toBe('/handle');
});

it('returns null for a method without the attribute', function () {
    expect(Attributes::onMethod(TestClass::class, 'plain', MethodAttribute::class))->toBeNull();
});

it('returns null for an existing method on a plain class', function () {
    expect(Attributes::onMethod(PlainClass::class, 'handle', MethodAttribute::class))->toBeNull();
});

it('returns null for a non-existent method', function () {
    expect(Attributes::onMethod(TestClass::class, 'nonExistent', MethodAttribute::class))->toBeNull();
});

// onProperty

it('can get an attribute from a property', function () {
    $attribute = Attributes::onProperty(TestClass::class, 'name', PropertyAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(PropertyAttribute::class)
        ->fillable->toBeTrue();
});

it('returns null for a non-existent property', function () {
    expect(Attributes::onProperty(TestClass::class, 'nonExistent', PropertyAttribute::class))->toBeNull();
});

it('returns null for an existing property without the attribute', function () {
    expect(Attributes::onProperty(PlainClass::class, 'name', PropertyAttribute::class))->toBeNull();
});

// onConstant

it('can get an attribute from a constant', function () {
    $attribute = Attributes::onConstant(TestClass::class, 'STATUS_ACTIVE', ConstantAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(ConstantAttribute::class)
        ->description->toBe('The active status');
});

it('returns null for a non-existent constant', function () {
    expect(Attributes::onConstant(TestClass::class, 'NON_EXISTENT', ConstantAttribute::class))->toBeNull();
});

it('returns null for an existing constant without the attribute', function () {
    expect(Attributes::onConstant(PlainClass::class, 'VALUE', ConstantAttribute::class))->toBeNull();
});

// onParameter

it('can get an attribute from a parameter', function () {
    $attribute = Attributes::onParameter(TestClass::class, 'handle', 'request', ParameterAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(ParameterAttribute::class)
        ->type->toBe('request');
});

it('returns null for a non-existent parameter', function () {
    expect(Attributes::onParameter(TestClass::class, 'handle', 'nonExistent', ParameterAttribute::class))->toBeNull();
});

it('returns null for a non-existent method on parameter lookup', function () {
    expect(Attributes::onParameter(TestClass::class, 'nonExistent', 'request', ParameterAttribute::class))->toBeNull();
});

// on* without filter

it('can get the first attribute from a method without filtering', function () {
    $attribute = Attributes::onMethod(TestClass::class, 'handle');

    expect($attribute)->toBeInstanceOf(MethodAttribute::class);
});

it('can get the first attribute from a property without filtering', function () {
    $attribute = Attributes::onProperty(TestClass::class, 'name');

    expect($attribute)->toBeInstanceOf(PropertyAttribute::class);
});

it('can get the first attribute from a constant without filtering', function () {
    $attribute = Attributes::onConstant(TestClass::class, 'STATUS_ACTIVE');

    expect($attribute)->toBeInstanceOf(ConstantAttribute::class);
});

it('can get the first attribute from a parameter without filtering', function () {
    $attribute = Attributes::onParameter(TestClass::class, 'handle', 'request');

    expect($attribute)->toBeInstanceOf(ParameterAttribute::class);
});

// onFunction

it('can get an attribute from a function', function () {
    $attribute = Attributes::onFunction('Spatie\\Attributes\\Tests\\TestSupport\\testFunction', MultiTargetAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(MultiTargetAttribute::class)
        ->label->toBe('standalone');
});

it('returns null for a function without the attribute', function () {
    expect(Attributes::onFunction('Spatie\\Attributes\\Tests\\TestSupport\\testFunction', SimpleAttribute::class))->toBeNull();
});

it('returns null for a non-existent function', function () {
    expect(Attributes::onFunction('nonExistentFunction', MultiTargetAttribute::class))->toBeNull();
});

it('can get the first attribute from a function without filtering', function () {
    $attribute = Attributes::onFunction('Spatie\\Attributes\\Tests\\TestSupport\\testFunction');

    expect($attribute)->toBeInstanceOf(MultiTargetAttribute::class);
});

// getAllOnFunction

it('can get all attributes from a function', function () {
    $attributes = Attributes::getAllOnFunction('Spatie\\Attributes\\Tests\\TestSupport\\testFunction', MultiTargetAttribute::class);

    expect($attributes)
        ->toHaveCount(1)
        ->sequence(
            fn ($attr) => $attr->toBeInstanceOf(MultiTargetAttribute::class),
        );
});

it('can get all attributes from a function without filtering', function () {
    $attributes = Attributes::getAllOnFunction('Spatie\\Attributes\\Tests\\TestSupport\\testFunction');

    expect($attributes)->toHaveCount(1);
});

it('returns empty array for getAllOnFunction with non-existent function', function () {
    expect(Attributes::getAllOnFunction('nonExistentFunction', MultiTargetAttribute::class))->toBeEmpty();
});

// IS_INSTANCEOF inheritance

it('finds child attributes when querying parent class', function () {
    $attribute = Attributes::get(ChildTestClass::class, SimpleAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(ChildAttribute::class)
        ->name->toBe('child-class');
});

it('finds child attributes with exact class too', function () {
    $attribute = Attributes::get(ChildTestClass::class, ChildAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(ChildAttribute::class)
        ->name->toBe('child-class');
});

// find

it('can find all attributes across a class', function () {
    $results = Attributes::find(TestClass::class, MultiTargetAttribute::class);

    expect($results)
        ->toHaveCount(1)
        ->each->toBeInstanceOf(AttributeTarget::class);

    expect($results[0])
        ->attribute->toBeInstanceOf(MultiTargetAttribute::class)
        ->attribute->label->toBe('processor')
        ->name->toBe('process');
});

it('finds attributes on class, methods, properties, constants, and parameters', function () {
    // Use a broad search with SimpleAttribute - via IS_INSTANCEOF, this won't match other attrs
    // Instead let's search for something present at multiple levels
    $results = Attributes::find(TestClass::class, ParameterAttribute::class);

    expect($results)->toHaveCount(2);

    expect($results[0])->name->toBe('handle.request');
    expect($results[1])->name->toBe('handle.id');
});

it('find returns back-references to reflection targets', function () {
    $results = Attributes::find(TestClass::class, MethodAttribute::class);

    expect($results)->toHaveCount(2);

    expect($results[0])
        ->target->toBeInstanceOf(ReflectionMethod::class)
        ->name->toBe('handle');

    expect($results[1])
        ->target->toBeInstanceOf(ReflectionMethod::class)
        ->name->toBe('process');
});

it('find returns empty array for class without matching attributes', function () {
    expect(Attributes::find(PlainClass::class, SimpleAttribute::class))->toBeEmpty();
});

it('can find all attributes without filtering by type', function () {
    $results = Attributes::find(TestClass::class);

    expect($results)
        ->toHaveCount(19)
        ->each->toBeInstanceOf(AttributeTarget::class);

    $attributeClasses = array_map(fn (AttributeTarget $r) => get_class($r->attribute), $results);

    expect($attributeClasses)->toContain(
        SimpleAttribute::class,
        RepeatableAttribute::class,
        RepeatableTag::class,
        MethodAttribute::class,
        PropertyAttribute::class,
        ConstantAttribute::class,
        ParameterAttribute::class,
        MultiTargetAttribute::class,
    );
});

it('find without filter returns empty array for plain class', function () {
    expect(Attributes::find(PlainClass::class))->toBeEmpty();
});

it('find discovers child attributes via inheritance', function () {
    $results = Attributes::find(ChildTestClass::class, SimpleAttribute::class);

    expect($results)->toHaveCount(1);
    expect($results[0]->attribute)->toBeInstanceOf(ChildAttribute::class);
});

// object instances

it('works with object instances for all methods', function () {
    $object = new TestClass;

    expect(Attributes::has($object, SimpleAttribute::class))->toBeTrue();
    expect(Attributes::onMethod($object, 'handle', MethodAttribute::class))->toBeInstanceOf(MethodAttribute::class);
    expect(Attributes::onProperty($object, 'name', PropertyAttribute::class))->toBeInstanceOf(PropertyAttribute::class);
    expect(Attributes::onConstant($object, 'STATUS_ACTIVE', ConstantAttribute::class))->toBeInstanceOf(ConstantAttribute::class);
    expect(Attributes::onParameter($object, 'handle', 'request', ParameterAttribute::class))->toBeInstanceOf(ParameterAttribute::class);
    expect(Attributes::find($object, MultiTargetAttribute::class))->toHaveCount(1);
});
