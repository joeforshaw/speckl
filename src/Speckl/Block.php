<?php

namespace Speckl;

class Block {
  use BlockTrait;

  public function __construct($label, $body, $parent, $path, $pending = false) {
    $this->initialise($label, $body, $parent, $path, $pending);
  }
}
