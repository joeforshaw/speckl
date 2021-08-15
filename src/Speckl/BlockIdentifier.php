<?php

namespace Speckl;

use ReflectionClass;

class BlockIdentifier {
  private $block;

  public function __construct($block) {
    $this->block = $block;
  }

  public static function create($parentBlock, $class, $args) {
    return $parentBlock->namespacedId() . '/' . static::buildId($class, $args['label'], $args['body']);
  }

  public function id() {
    return static::buildId(get_class($this->block), $this->block->label, $this->block->body);
  }

  public function namespacedId() {
    return $this->block->parentBlock->namespacedId() . '/' . $this->block->id();
  }

  private static function buildId($class, $label, $body) {
    $shortName = strtolower((new ReflectionClass($class))->getShortName());
    return $shortName . '("' . $label . '",' . BodyHash::from($body) . '")';
  }
}
