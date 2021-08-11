<?php

namespace Speckl;

class GroupBlock extends Block {
  public function loadBlock() {
    if ($this->isRootBlock()) {
      $this->runner = Container::get('runner');
      $this->runner->addBlock($this);
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
