<?php

namespace Speckl\Formatters;

use Exception;
use Speckl\Block;
use Speckl\Container;

abstract class Formatter {
  protected $output;

  public function __construct(Exception $exception, Block $block) {
    $this->block = $block;
    $this->exception = $exception;
  }

  public final function formatHeader($index) {
    $output = "\n" . ($index + 1 ) . ') ' . $this->block->sentence() . ":\n";
    return $output . Container::get('divider');
  }

  public abstract function format();
}
