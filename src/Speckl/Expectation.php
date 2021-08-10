<?php

namespace Speckl;

class Expectation {
  public $actual,
         $lineNumber,
         $to,
         $toBe,
         $toNot,
         $toNotBe;

  public function __construct($actual, $lineNumber) {
    $this->actual = $actual;
    $this->lineNumber = $lineNumber;
    $this->to = new Constraint($this, false);
    $this->toBe = $this->to;
    $this->toNot = new Constraint($this, true);
    $this->toNotBe = $this->toNot;
  }
}
