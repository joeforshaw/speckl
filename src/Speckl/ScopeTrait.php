<?php

namespace Speckl;

trait ScopeTrait {
  public $debugLabel;
  private $parentScope, $callables;

  public function __construct($debugLabel, $parentScope) {
    $this->debugLabel = $debugLabel;
    $this->parentScope = $parentScope;
    $this->callables = [];
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

  public function __set($name, $value) {
    if (is_callable($value)) {
      $this->callables[$name] = $value;
    }
    $this->$name = $value;
  }

  public function bindCallables($scope) {
    if ($this->parentScope) {
      $this->parentScope->bindCallables($scope);
    }
    foreach ($this->callables as $name => $callable) {
      $this->$name = $callable->bindTo($scope, $scope);
    }
  }

  public function isRootScope() {
    return is_null($this->parentScope);
  }

  public function debug() {
    return $this->debugLabel;
  }

  // Intentionally empty to allow extension
  public function beforeCallback() {}
  public function afterCallback() {}
}
