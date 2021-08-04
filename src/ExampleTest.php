<?php

include 'TestFailure.php';
include 'Expectation.php';
include 'Nodes/Scope.php';
include 'Nodes/Node.php';
include 'Nodes/Describe.php';
include 'Nodes/It.php';
include 'Functions.php';

class Dog {
  public $isHappy;

  public function isGood() {
    return true;
  }

  public function tailIsWagging() {
    return $this->isHappy;
  }
}

describe(Dog::class, function() {
  beforeEach(function() {
    $this->dog = new Dog();
  });

  afterEach(function() {
    // Do stuff after
  });

  context('when the dog is happy', function() {
    it("wags it's tail", function() {
      $this->dog->isHappy = true;
      expect($this->dog->tailIsWagging())->to->equal(true);
    });
  });

  it('is a good doggo', function() {
    expect($this->dog->isGood())->to->equal(true);
  });

  it('is a bad doggo', function() {
    expect($this->dog->isGood())->to->equal(false);
  });
});
