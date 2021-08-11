<?php

namespace Speckl;

class Expectation {
  public $actual,
         $lineNumber;

  public function __construct($actual, $lineNumber) {
    $this->actual = $actual;
    $this->lineNumber = $lineNumber;
  }

  public function __get($name) {
    if ($this->allowedTo($name)) {
      return new Constraint($this, false);
    }
    if ($this->allowedToNot($name)) {
      return new Constraint($this, true);
    }
  }

  public function allowedTo($name) {
    return in_array($name, ['to', 'toBe', 'toBeA']);
  }

  public function allowedToNot($name) {
    return in_array($name, ['toNot', 'toNotBe', 'toNotBeA']);
  }
}
