<?php

namespace Speckl;

trait ScopeTrait {
  public $debugLabel;
  private $parentScope;

  public function __construct($debugLabel, $parentScope) {
    $this->debugLabel = $debugLabel;
    $this->parentScope = $parentScope;
  }

  public function __call($name, $arguments) {
    if (is_callable($this->$name)) {
      return call_user_func_array($this->$name, $arguments);
    }
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

  // Intentionally empty to allow extension
  public function beforeCallback() {}
  public function afterCallback() {}
}
