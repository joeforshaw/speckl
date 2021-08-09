<?php

namespace Speckl;

use Error;

class ExampleBlock extends Block implements RunnableBlock {
  public function runBlock() {
    if ($this->isPending()) {
      echo "\033[33m" . $this->indentedLabel() . "\033[0m";
      return;
    }
    try {
      $this->scope->beforeCallback();
      $this->scope->bindCallables($this->scope);
      $this->runSharedContexts($this);
      $this->runBeforeCallbacks();
      $this->runBody();
      echo "\033[32m" . $this->indentedLabel() . "\033[0m";
    } catch (TestFailure $failure) {
      echo "\033[01;31m" . $this->indentedLabel() . "\033[0m";
    } catch(Error $e) {
      echo $e;
      die;
    } finally {
      $this->runAfterCallbacks();
      $this->scope->afterCallback();
    }
  }
}
