<?php

namespace Speckl;

abstract class Block {
  use BlockTrait;

  public function __construct($args) {
    $this->initialise($args);
  }

  public abstract function runBlock();
}
