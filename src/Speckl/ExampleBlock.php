<?php

namespace Speckl;

use Error;
use Exception;

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
    } catch (Exception $exception) {
      $this->handle($exception);
      echo "\033[01;31m" . $this->indentedLabel() . "\033[0m";
    } catch (Error $error) {
      $this->handle($error);
      echo "\033[01;31m" . $this->indentedLabel() . "\033[0m";
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
