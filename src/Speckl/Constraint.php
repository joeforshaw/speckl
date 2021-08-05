<?php

namespace Speckl;

class Constraint {
  public function __construct($actual, $negated) {
    $this->actual = $actual;
    $this->negated = $negated;
  }

  public function true() { $this->check($this->actual === true); }
  public function beTrue() { $this->true(); }

  public function false() { $this->check($this->actual === false); }
  public function beFalse() { $this->false(); }

  public function null() { $this->check(is_null($this->actual)); }
  public function beNull() { $this->null(); }

  public function string() { $this->check(is_string($this->actual)); }
  public function beString() { $this->string(); }

  public function empty() { $this->check(empty($this->actual)); }
  public function beEmpty() { $this->empty(); }

  public function theSameSizeAs($exp) { $this->check(count($this->actual) === count($exp)); }
  public function beTheSameSizeAs($exp) { $this->theSameSizeAs($exp); }

  public function AnInstanceOf($class) { $this->check($this->actual instanceof $class); }
  public function beAnInstanceOf($class) { $this->AnInstanceOf($class); }

  public function equal($exp) { $this->check($this->actual === $exp, $exp); }
  public function equalTo($exp) { $this->equal($exp); }
  public function eq($exp) { $this->equal($exp); }
  public function equals($exp) { $this->equal($exp); }

  public function greaterThan($exp) { $this->check($this->actual > $exp, $exp); }
  public function beGreaterThan($exp) { $this->greaterThan($exp); }

  public function greaterThanOrEqualTo($exp) { $this->check($this->actual >= $exp, $exp); }
  public function beGreaterThanOrEqualTo($exp) { $this->greaterThanOrEqualTo($exp); }
  public function gte($exp) { $this->greaterThanOrEqualTo($exp); }

  public function lessThan($exp) { $this->check($this->actual < $exp, $exp); }
  public function beLessThan($exp) { $this->lessThan($exp); }

  public function lessThanOrEqualTo($exp) { $this->check($this->actual <= $exp, $exp); }
  public function belessThanOrEqualTo($exp) { $this->lessThanOrEqualTo($exp); }
  public function lte($exp) { $this->lessThanOrEqualTo($exp); }

  public function haveKey($key) { array_key_exists($key, $this->actual); }

  private function check($boolean, $exp = null) {
    $this->expected = $exp;
    if ($this->negated) { $boolean = !$boolean; }
    if (!$boolean) {
      throw new TestFailure($this->actual, $this->expected);
    }
  }
}
