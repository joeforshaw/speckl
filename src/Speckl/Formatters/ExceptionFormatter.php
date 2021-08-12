<?php

namespace Speckl\Formatters;

use Speckl\Formatters\Formatter;

class ExceptionFormatter extends Formatter {
  public function format() {
    $output = '"' . get_class($this->exception) . '"';
    if ($this->exception->getMessage()) {
      $output .= ' with message "' . $this->exception->getMessage() . '"';
    }
    $output .= " raised\nStack trace:\n" . $this->exception->getTraceAsString();
    return $output;
  }
}
