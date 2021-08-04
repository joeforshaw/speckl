<?php

namespace Speckl;

use \Exception;
use \Throwable;

class TestFailure extends Exception {
  public function __construct($expected, $actual, $code = 0, Throwable $previous = null) {
    $this->expected = $expected;
    $this->actual = $actual;
    parent::__construct($this->createMessage(), $code, $previous);
  }

  private function createMessage() {
    return 'Expected: '
      . var_export($this->expected, true)
      . ', Actual: '
      . var_export($this->actual, true);
  }
}
