<?php

namespace Speckl;

use Error;
use Exception;
use ReflectionClass;

trait Example {
  use BlockTrait;

  public function loadBlock() {
    $this->parentBlock->addChildBlock($this);
  }

  public function runBlock() {
    if ($this->isPending()) {
      echo $this->indentedLabel("\033[33m");
      return;
    }
    try {
      $this->scope->beforeCallback();
      $this->scope->subject = $this->intializeImplicitSubject();
      $this->scope->bindCallables($this->scope);
      $this->runSharedContexts($this);
      $this->runBeforeCallbacks();
      $this->runBody();
      echo $this->indentedLabel("\033[32m");
    } catch (Exception $exception) {
      $this->handle($exception);
      echo $this->indentedLabel("\033[01;31m");
    } catch (Error $error) {
      $this->handle($error);
      echo $this->indentedLabel("\033[01;31m");
    } finally {
      $this->runAfterCallbacks();
      $this->scope->afterCallback();
    }
  }

  private function handle($throwable) {
    $failureHandlerClass = Container::get('failureHandlerClass');
    (new $failureHandlerClass())->handle($this, $throwable);
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
}
