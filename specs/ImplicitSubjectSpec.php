<?php

use Speckl\TestClasses\TestClassWithNoConstructor;
use Speckl\TestClasses\TestClassWithParameterlessConstructor;
use Speckl\TestClasses\TestClassWithConstructorWithParameters;

describe(TestClassWithNoConstructor::class, function() {
  it('initializes an implicit subject', function() {
    expect($this->subject)->toBe->AnInstanceOf(TestClassWithNoConstructor::class);
  });
});

describe(TestClassWithParameterlessConstructor::class, function() {
  it('initializes an implicit subject', function() {
    expect($this->subject)->toBe->AnInstanceOf(TestClassWithParameterlessConstructor::class);
  });
});

describe(TestClassWithConstructorWithParameters::class, function() {
  it("doesn't initialize an implicit subject", function() {
    expect($this->subject)->to->beNull();
  });
});
