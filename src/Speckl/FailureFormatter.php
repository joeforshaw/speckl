<?php

namespace Speckl;

class FailureFormatter {
  public function __construct(Block $block, $throwable = null) {
    $this->block = $block;
    $this->throwable = $throwable;
  }

  public function output($index) {
    $output = ($index + 1 ) . ') ' . $this->block->sentence() . ":\n";
    $output .= "-------------------------------------------------------\n";
    if ($this->throwable) {
      if ($this->throwable instanceof Failure) {
        $expectation = $this->throwable->constraint->expectation;
        $output .= $this->throwable->getMessage() . "\n\n";
        $output .= $this->block->filePath() . ':' . $expectation->lineNumber;
      } else {
        $output .= $this->throwable;
      }
    }
    return $output . "\n";
  }
}
