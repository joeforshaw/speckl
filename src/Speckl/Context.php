<?php

namespace Speckl;

class Context implements Block {
  use GroupBlockTrait;

  public function sentencePart() {
    return ', ' . $this->label . ',';
  }
}
