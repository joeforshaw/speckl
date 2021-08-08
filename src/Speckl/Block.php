<?php

namespace Speckl;

class Block {
  use BlockTrait;

  public function __construct($args) {
    $this->initialise($args);
  }
}
