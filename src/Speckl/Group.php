<?php

namespace Speckl;

trait Group {
  use Blockish;

  public function loadBlock() {
    if (!$this->shouldRun()) { return; }
    $this->parentBlock->addChildBlock($this);
  }

  public function runBlock() {
    if (!$this->shouldRun()) { return; }
    echo $this->indentedLabel();
    $this->setupScope();
    $this->runBody();
  }
}
