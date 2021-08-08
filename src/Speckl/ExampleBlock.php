<?php

namespace Speckl;

class ExampleBlock extends Block {
  public function runBlock() {
    if ($this->isPending()) {
      echo "\033[33m" . $this->indentedLabel() . "\033[0m";
      return;
    }

    try {
      $this->runBeforeCallbacks();
      $this->runBody();
      echo "\033[32m" . $this->indentedLabel() . "\033[0m";
    } catch (TestFailure $failure) {
      echo "\033[01;31m" . $this->indentedLabel() . "\033[0m";
    } finally {
      $this->runAfterCallbacks();
    }
  }
}
