<?php

namespace Speckl;

use Speckl\Block;
use Speckl\Config;

class Runner {
  const SUCCESS_EXIT   = 0;
  const FAILURE_EXIT   = 1;
  const EXCEPTION_EXIT = 2;

  public function __construct($files) {
    $this->files = $files;
  }

  public function run() {
    Config::set('currentBlock', null);
    Config::set('currentPath', null);
    if (!Config::get('blockClass')) {
        Config::set('blockClass', Block::class);
    }
    
    foreach ($this->files as $filePath) {
        Config::set('currentPath', $filePath);
        include $filePath;
    }

    return Runner::SUCCESS_EXIT;
  }
}
