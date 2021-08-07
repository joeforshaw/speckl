<?php

namespace Speckl;

use Speckl\Block;

class Runner {
  public function main($files) {
    if (!array_key_exists('speckl', $GLOBALS)) {
        $GLOBALS['speckl'] = [];
    }
    $GLOBALS['speckl']['currentBlock'] = null;
    $GLOBALS['speckl']['currentPath'] = null;
    if (!$GLOBALS['speckl']['blockClass']) {
        $GLOBALS['speckl']['blockClass'] = Block::class;
    }
    
    foreach ($files as $filePath) {
        $GLOBALS['speckl']['currentPath'] = $filePath;
        include $filePath;
    }
  }
}
