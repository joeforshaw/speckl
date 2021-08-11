<?php

namespace Speckl;

class Context implements Block {
  use Group;

  public function sentencePart() {
    return ', ' . $this->label . ',';
  }
}
