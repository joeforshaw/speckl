<?php

class TestClassWithNoConstructor {}

class TestClassWithParameterlessConstructor {
  public function __construct() {}
}

class TestClassWithConstructorWithParameters {
  public function __construct($parameter) {}
}

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
