<?php

include 'TestFailure.php';
include 'Expectation.php';
include 'TreeNode.php';
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
  it('is a good doggo', function() {
    $dog = new Dog();
    expect($dog->isGood())->to->equal(true);
  });

  it('is not not a good doggo', function() {
    $dog = new Dog();
    expect($dog->isGood())->toNot->equal(false);
  });

  context('when the dog is happy', function() {
    it("wags it's tail", function() {
      $dog = new Dog();
      $dog->isHappy = true;
      expect($dog->tailIsWagging())->to->equal(true);
    });
  });
});
