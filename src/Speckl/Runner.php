<?php

namespace Speckl;

class Runner {
  const SUCCESS_EXIT   = 0;
  const FAILURE_EXIT   = 1;
  const EXCEPTION_EXIT = 2;

  private $files,
          $blocks,
          $sharedContexts,
          $sharedExamples;

  public function __construct($files) {
    $this->files = $files;
    $this->blocks = [];
    $this->sharedContexts = [];
    $this->sharedExamples = [];
  }

  public function run() {
    Container::set('runner', $this);

    $this->loadLocalConfig();

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
    $failHandler = new FailureHandler();
    if ($failHandler->anyFails()) {
      $failHandler->outputFails();
      $exitCode = Runner::FAILURE_EXIT;
    } else {
      $exitCode = Runner::SUCCESS_EXIT;
    }

    $this->outputStats();

    return $exitCode;
  }

  private function loadLocalConfig() {
    $localConfigPath = getcwd() . "/specs/Config.php";
    if (file_exists($localConfigPath)) { require_once $localConfigPath;}
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

  public function addSharedExamples($label, $body) {
    $this->sharedExamples[$label] = $body;
  }

  public function getSharedExamples($label) {
    return $this->sharedExamples[$label];
  }

  public function extractLineNumber($filePath) {
    $parts = explode(':', $filePath);
    if (count($parts) === 2 && is_numeric($parts[1])) {
      return [$parts[0], intval($parts[1])];
    }
    return [$filePath];
  }

  public function outputStats() {
    $output = "\n" . Container::get('totalCount') . " examples";
    if (Container::get('pendingCount')) {
      $output .= ", \033[33m" . Container::get('pendingCount') . " pending\033[0m";
    }
    if (Container::get('failureCount')) {
      $output .= ", \033[01;31m" . Container::get('failureCount') . " failed\033[0m";
    } else {
      $output .= ', 0 failed';
    }
    echo $output . "\n\n";
  }
}
