<?php

namespace Speckl;

use Error;

class ExampleBlock extends Block implements RunnableBlock {
  public function __construct($args) {
    parent::__construct($args); 
    $this->prependBeforeCallback(function() {
      $this->scope->beforeCallback();
    });
    $this->addAfterCallback(function() {
      $this->scope->afterCallback();
    });
  }

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
    } catch(Error $e) {
      echo $e;
      die;
    } finally {
      $this->runAfterCallbacks();
    }
  }
}
