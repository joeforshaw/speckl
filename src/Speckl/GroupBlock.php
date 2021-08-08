<?php

namespace Speckl;

class GroupBlock extends Block {
  public function loadBlock() {
    if ($this->isRootBlock()) {
      $this->runner = Config::get('runner');
      $this->runner->registerBlock($this);
    }
    $this->runBody();
  }

  public function runBlock() {
    echo $this->indentedLabel();
    foreach ($this->childBlocks as $block) {
      $block->runBlock();
    }
  }
}
