<?php

namespace Speckl;

abstract class Block implements RunnableBlock {
  use BlockTrait;

  public function __construct($args) {
    $this->initialise($args);
  }
}
