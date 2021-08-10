<?php

namespace Speckl;

class TestFailureFormatter {
  public function __construct(Block $block, $throwable = null) {
    $this->block = $block;
    $this->throwable = $throwable;
  }

  public function output($index) {
    $output = ($index + 1 ) . ') ' . $this->block->sentence() . ":\n";
    $output .= "-------------------------------------------------------\n";
    if ($this->throwable) {
      if ($this->throwable instanceof TestFailure) {
        $expectation = $this->throwable->constraint->expectation;
        $output .= $this->throwable->getMessage() . "\n\n";
        $output .= $this->block->filePath() . ':' . $expectation->lineNumber . "\n";
      } else {
        $output .= $this->throwable;
      }
    }
    return $output;
  }
}
