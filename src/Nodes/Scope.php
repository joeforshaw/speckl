<?php

namespace Speckl\Nodes;

class Scope {
  public function __construct($parent) {
    $this->parent = $parent;
  }

  // Adopts a decorator pattern, wrapping the parent's scope
  public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    }
    if ($this->parent) {
      return $this->parent->$property;
    }
  }
}
