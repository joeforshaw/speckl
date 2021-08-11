<?php

class TestClassWithNoConstructor {}

class TestClassWithParameterlessConstructor {
  public function __construct() {}
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
