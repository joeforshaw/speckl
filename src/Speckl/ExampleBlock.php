<?php

namespace Speckl;

use Error;
use Exception;

class ExampleBlock extends Block implements RunnableBlock {
  public function runBlock() {
    if ($this->isPending()) {
      echo $this->indentedLabel("\033[33m");
      return;
    }
    try {
      $this->scope->beforeCallback();
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
    $failHandlerClass = Container::get('failHandlerClass');
    (new $failHandlerClass())->handle($this, $throwable);
  }
}
