<?php

namespace Speckl\Formatters;

class FailureFormatter extends Formatter {
  public function format() {
    $expectation = $this->exception->constraint->expectation;
    $output = $this->exception->getMessage() . "\n\n";
    $output .= $this->block->filePath() . ':' . $expectation->lineNumber;
    return $output;
  }
}
