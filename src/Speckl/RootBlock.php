<?php

namespace Speckl;

class RootBlock {
  public $parentBlock,
         $childBlocks;

  public function __construct() {
    $this->parentBlock = null;
    $this->childBlocks = [];
  }

  public function addChildBlock($block) {
    array_push($this->childBlocks, $block);
  }

  public function indentation() { return -2; }
  public function sentencePart() { return null; }
  public function isRootBlock() { return true; }
  public function containsSelectedLineNumber() { return false; }
  public function shouldRun() { return false; }
  public function ancestorShouldRun() { return false; }
  public function id() { return 'Speckl\RootBlack'; }
  public function namespacedId() { return null; }
}
