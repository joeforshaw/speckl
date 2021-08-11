<?php

namespace Speckl;

class Runner {
  const SUCCESS_EXIT   = 0;
  const FAILURE_EXIT   = 1;
  const EXCEPTION_EXIT = 2;

  private $files,
          $blocks,
          $sharedContexts;

  public function __construct($files) {
    $this->files = $files;
    $this->blocks = [];
    $this->sharedContexts = [];
  }

  public function run() {
    Container::set('runner', $this);

    // Load the spec tree
    foreach ($this->files as $filePath) {
      list($filePath, $lineNumber) = $this->extractLineNumber($filePath);
      if ($lineNumber) {
        Container::set('selectedLineNumber', $lineNumber);
      }
      require_once $filePath;
    }

    // Run the spec tree
    foreach ($this->blocks as $block) {
      $block->runBlock();
    }

    // Output fails
    $failureHandlerClass = Container::get('failureHandlerClass');
    $failHandler = new $failureHandlerClass();
    if ($failHandler->anyFails()) {
      $failHandler->outputFails();
    }

    return Runner::SUCCESS_EXIT;
  }

  public function addBlock($block) {
    array_push($this->blocks, $block);
  }

  public function addSharedContext($label, $body) {
    $this->sharedContexts[$label] = $body;
  }

  public function getSharedContext($label) {
    return $this->sharedContexts[$label];
  }

  public function extractLineNumber($filePath) {
    $parts = explode(':', $filePath);
    if (count($parts) === 2 && is_numeric($parts[1])) {
      return [$parts[0], intval($parts[1])];
    }
    return [$filePath];
  }
}
