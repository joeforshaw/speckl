<?php

namespace Speckl;

use Exception;

class Constraint {
  public function __construct($expectation, $negated) {
    $this->expectation = $expectation;
    $this->actual = $this->expectation->actual;
    $this->negated = $negated;
  }

  public function true() { $this->check($this->actual === true, true); }
  public function beTrue() { $this->true(); }

  public function false() { $this->check($this->actual === false, false); }
  public function beFalse() { $this->false(); }

  public function null() { $this->check(is_null($this->actual), null); }
  public function beNull() { $this->null(); }

  public function string() { $this->check(is_string($this->actual), 'string'); }
  public function aString() { $this->string(); }
  public function beAString() { $this->string(); }

  public function beEmpty() { $this->check(empty($this->actual), 'empty'); }

  public function theSameSizeAs($exp) { $this->check(count($this->actual) === count($exp), 'length of ' . count($exp)); }
  public function beTheSameSizeAs($exp) { $this->theSameSizeAs($exp); }

  public function AnInstanceOf($class) { $this->check($this->actual instanceof $class, "an instance of $class"); }
  public function beAnInstanceOf($class) { $this->AnInstanceOf($class); }

  public function equal($exp) { $this->check($this->actual === $exp, $exp); }
  public function equalTo($exp) { $this->equal($exp); }
  public function eq($exp) { $this->equal($exp); }

  public function greaterThan($exp) { $this->check($this->actual > $exp, "greater than $exp"); }
  public function beGreaterThan($exp) { $this->greaterThan($exp); }

  public function greaterThanOrEqualTo($exp) { $this->check($this->actual >= $exp, "greater than or equal to $exp"); }
  public function beGreaterThanOrEqualTo($exp) { $this->greaterThanOrEqualTo($exp); }
  public function gte($exp) { $this->greaterThanOrEqualTo($exp); }

  public function lessThan($exp) { $this->check($this->actual < $exp, "less than $exp"); }
  public function beLessThan($exp) { $this->lessThan($exp); }

  public function lessThanOrEqualTo($exp) { $this->check($this->actual <= $exp, "less than or equal to $exp"); }
  public function belessThanOrEqualTo($exp) { $this->lessThanOrEqualTo($exp); }
  public function lte($exp) { $this->lessThanOrEqualTo($exp); }

  public function haveKey($key) { $this->check(array_key_exists($key, $this->actual), "have key \"$key\""); }

  public function raiseAnException() {
    try {
      call_user_func($this->actual);
    } catch (Exception $e) {
      $this->check(true, "raise an exception");
      return;
    }
    $this->check(false, "raise an exception");
  }

  public function fail() {
    try {
      call_user_func($this->actual);
    } catch (Failure $failure) {
      $this->check(true, "test fail");
      return;
    }
    $this->check(false, "test fail");
  }

  private function check($boolean, $exp) {
    $this->expected = $exp;
    if ($this->negated) { $boolean = !$boolean; }
    if (!$boolean) {
      $failure = new Failure($this->failureMessage());
      $failure->constraint = $this;
      throw $failure;
    }
  }

  private function failureMessage() {
    $expected = is_string($this->expected)
      ? $this->expected
      : var_export($this->expected, true);

    return 'Expected: ' . $expected . "\n" .
           'Actual: ' . var_export($this->actual, true);
  }
}
