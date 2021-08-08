<?php

namespace Speckl;

class Scope {
  private $parentScope;

  public function __construct($parentScope) {
    $this->parentScope = $parentScope;
  }

  // Adopts a decorator pattern, wrapping the parent's scope
  public function __get($property) {
    if (property_exists($this, $property)) {
      return $this->$property;
    }
    if ($this->parentScope) {
      return $this->parentScope->$property;
    }
  }
}
