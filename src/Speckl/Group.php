<?php

namespace Speckl;

trait Group {
  use Blockish;

  public function loadBlock() {
    if (!$this->containsSelectedLineNumber()) { return; }
    if ($this->isRootBlock()) {
      $this->runner = Container::get('runner');
      $this->runner->addBlock($this);
    } else {
      $this->parentBlock->addChildBlock($this);
    }
    if (!$this->lazy) {
      $this->runBody();
    }
  }

  public function runBlock() {
    echo $this->indentedLabel();
    $aChildBlockContainsSelectedLineNumber = $this->anyChildBlockContainsSelectedLineNumber();

    // Resolve lazy blocks
    $resolving = true;
    do { $resolving = $this->resolveNextLazyBlock(); } while ($resolving);

    foreach ($this->childBlocks as $block) {
      if (!$aChildBlockContainsSelectedLineNumber || $block->containsSelectedLineNumber()) {
        $block->runBlock();
      }
    }
  }

  private function anyChildBlockContainsSelectedLineNumber() {
    if (!$this->selectedLineNumberSet()) { return false; }
    foreach ($this->childBlocks as $block) {
      if ($block->containsSelectedLineNumber()) { return true; }
    }
    return false;
  }
}
