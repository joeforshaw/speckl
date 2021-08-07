<?php

namespace Speckl;

class Block
{
  use BlockTrait;

  public function __construct($label, $body, $parent, $path)
  {
    $this->initialise($label, $body, $parent, $path);
  }
}
