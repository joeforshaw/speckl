<?php

namespace Speckl;

class Expectation {
  private $negated = false;

  public function __construct($actual) {
    $this->actual = $actual;
  }

  public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    }
    if ($property == 'to') {
      return $this;
    }
    if ($property == 'toNot') {
      $this->negated = true;
      return $this;
    }
  }

  public function equal($expected) {
    $this->expected = $expected;
    $this->check($this->actual === $this->expected);
  }

  private function check($boolean) {
    if ($this->negated) { $boolean = !$boolean; }
    if (!$boolean) {
      throw new TestFailure($this->actual, $this->expected);
    }
  }
}
