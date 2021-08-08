<?php

namespace Speckl;

use Speckl\Config;

class Runner {
  const SUCCESS_EXIT   = 0;
  const FAILURE_EXIT   = 1;
  const EXCEPTION_EXIT = 2;

  private $files, $blocks;

  public function __construct($files) {
    $this->files = $files;
    $this->blocks = [];
  }

  public function run() {
    Config::set('runner', $this);
    Config::set('currentBlock', null);
    Config::set('currentPath', null);
    if (!Config::get('blockClass')) {
      Config::set('blockClass', ExampleBlock::class);
    }

    // Load the spec tree
    foreach ($this->files as $filePath) {
      Config::set('currentPath', $filePath);
      include $filePath;
    }

    // Run the spec tree
    foreach ($this->blocks as $block) {
      $block->runBlock();
    }

    return Runner::SUCCESS_EXIT;
  }

  public function registerBlock($block) {
    array_push($this->blocks, $block);
  }
}
