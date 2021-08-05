<?php

namespace Speckl;

class Expectation {
  public $to, $toBe, $toNot, $toNotBe;

  public function __construct($actual) {
    $this->actual = $actual;
    $this->to = new Constraint($this->actual, false);
    $this->toBe = $this->to;
    $this->toNot = new Constraint($this->actual, true);
    $this->toNotBe = $this->toNot;
  }
}
