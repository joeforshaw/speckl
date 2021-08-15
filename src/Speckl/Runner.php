<?php

namespace Speckl;

class Runner {
  const SUCCESS_EXIT   = 0;
  const FAILURE_EXIT   = 1;
  const EXCEPTION_EXIT = 2;

  private $files,
          $rootBlock,
          $sharedBlocks,
          $blockIndex;

  public function __construct($files) {
    $this->files = $files;
    $this->sharedBlocks = [];
    $this->rootBlock = new RootBlock();
    $this->blockIndex = [];
  }

  public function run() {
    Container::set('runner', $this);
    Container::set('currentBlock', $this->rootBlock);

    $this->loadLocalConfig();

    // Load the spec tree
    Container::set('loading', true);
    $this->runFiles();
    Container::set('loading', false);

    // Run the spec
    $this->runFiles();

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

  private function runFiles() {
    foreach ($this->files as $filePath) {
      list($filePath, $lineNumber) = $this->extractLineNumber($filePath);
      if ($lineNumber) {
        Container::set('selectedLineNumber', $lineNumber);
      }
      require $filePath;
    }
  }

  private function loadLocalConfig() {
    $localConfigPath = getcwd() . "/specs/Config.php";
    if (file_exists($localConfigPath)) { require_once $localConfigPath;}
  }

  public function addSharedBlock($label, $body) {
    $this->sharedBlocks[$label] = $body;
  }

  public function getSharedBlock($label) {
    return $this->sharedBlocks[$label];
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

  public function loadBlock($block) {
    $block->loadBlock();
    $this->blockIndex[$block->namespacedId()] = $block;
  }

  public function getLoadedBlock($class, $label) {
    $parentBlock = Container::get('currentBlock');
    $indexKey = BlockIdentifier::create($parentBlock, $class, $label);
    return $this->blockIndex[$indexKey];
  }
}
