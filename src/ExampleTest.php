<?php

include 'TestFailure.php';
include 'Expectation.php';
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
    // echo "Before each\n";
  });

  afterEach(function() {
    // echo "After each\n";
  });

  context('when the dog is happy', function() {
    it("wags it's tail", function() {
      $dog = new Dog();
      $dog->isHappy = true;
      expect($dog->tailIsWagging())->to->equal(true);
    });
  });

  it('is a good doggo', function() {
    $dog = new Dog();
    expect($dog->isGood())->to->equal(true);
  });

  it('is a bad doggo', function() {
    $dog = new Dog();
    expect($dog->isGood())->to->equal(false);
  });
});
