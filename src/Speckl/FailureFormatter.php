<?php

namespace Speckl;

use Speckl\Exceptions\Failure;

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
        $output .= '"' . get_class($this->throwable) . '"';
        if ($this->throwable->getMessage()) {
          $output .= ' with message "' . $this->throwable->getMessage() . '"';
        }
        $output .= " raised\nStack trace:\n" . $this->throwable->getTraceAsString();
      }
    }
    return $output . "\n";
  }
}
