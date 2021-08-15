<?php

namespace Speckl;

use ReflectionClass;

class BlockIdentifier {
  private $block;

  public function __construct($block) {
    $this->block = $block;
  }

  public static function create($block, $class, $label) {
    $shortName = strtolower((new ReflectionClass($class))->getShortName());
    return $block->namespacedId() . '/' . $shortName . '("' . $label . '")';
  }

  public function id() {
    $shortName = strtolower((new ReflectionClass($this->block))->getShortName());
    return $shortName . '("' . $this->block->label . '")';
  }

  public function namespacedId() {
    return $this->block->parentBlock->namespacedId() . '/' . $this->block->id();
  }
}
