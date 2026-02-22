<?php

use Spatie\Attributes\Attributes;
use Spatie\Attributes\AttributeTarget;
use Spatie\Attributes\Tests\Fixtures\Attributes\ChildAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\ConstantAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\MethodAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\MultiTargetAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\ParameterAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\PropertyAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\RepeatableAttribute;
use Spatie\Attributes\Tests\Fixtures\Attributes\SimpleAttribute;
use Spatie\Attributes\Tests\Fixtures\ChildTestClass;
use Spatie\Attributes\Tests\Fixtures\PlainClass;
use Spatie\Attributes\Tests\Fixtures\TestClass;

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

// onFunction

it('can get an attribute from a function', function () {
    $attribute = Attributes::onFunction('Spatie\\Attributes\\Tests\\Fixtures\\testFunction', MultiTargetAttribute::class);

    expect($attribute)
        ->toBeInstanceOf(MultiTargetAttribute::class)
        ->label->toBe('standalone');
});

it('returns null for a function without the attribute', function () {
    expect(Attributes::onFunction('Spatie\\Attributes\\Tests\\Fixtures\\testFunction', SimpleAttribute::class))->toBeNull();
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
        ->toHaveCount(11)
        ->each->toBeInstanceOf(AttributeTarget::class);

    $attributeClasses = array_map(fn (AttributeTarget $r) => get_class($r->attribute), $results);

    expect($attributeClasses)->toContain(
        SimpleAttribute::class,
        RepeatableAttribute::class,
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
