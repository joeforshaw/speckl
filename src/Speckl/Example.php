<?php

namespace Speckl;

use Exception;
use ReflectionClass;

trait Example {
  use Blockish;

  public function loadBlock() {
    $this->parentBlock->addChildBlock($this);
  }

  public function runBlock() {
    try {
      if ($this->isPending()) {
        echo $this->indentedLabel("\033[33m");
        $this->incrementCount('pending');
        return;
      }

      $this->scope->beforeCallback();
      $this->scope->subject = $this->intializeImplicitSubject();
      $this->scope->bindCallables($this->scope);
      $this->runBeforeCallbacks();
      $this->runBody();
      $this->incrementCount('success');
      echo $this->indentedLabel("\033[32m");
    } catch (Exception $exception) {
      $this->runBlockCatch($exception);
    } finally {
      $this->runBlockFinally();
    }
  }

  private function runBlockCatch($exception) {
    (new FailureHandler())->handle($this, $exception);
    $this->incrementCount('failure');
    echo $this->indentedLabel("\033[01;31m");
  }

  private function runBlockFinally() {
    $this->runAfterCallbacks();
    $this->scope->afterCallback();
    $this->incrementCount('total');
  }

  private function intializeImplicitSubject() {
    $block = $this->parentBlock;
    while(!is_null($block)) {
      if ($this->hasParameterlessConstructor($block->label)) {
        $subjectClass = $block->label;
        return new $subjectClass();
      }
      $block = $block->parentBlock;
    }
    return null;
  }

  private function hasParameterlessConstructor($label) {
    if (!class_exists($label)) { return false; }
    $reflectionClass = new ReflectionClass($label);
    $constructor = $reflectionClass->getConstructor();
    return !$constructor || empty($constructor->getParameters());
  }

  private function incrementCount($key) {
    Container::set($key . 'Count', Container::get($key . 'Count') + 1);
  }
}
