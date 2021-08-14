<?php

namespace Speckl;

trait ScopeTrait {
  private $__block,
          $__parentScope,
          $__callables,
          $__debugLabel,
          $subject;

  public function __construct($block, $parentScope, $debugLabel) {
    $this->__block = $block;
    $this->__parentScope = $parentScope;
    $this->__callables = [];
    $this->__debugLabel = $debugLabel;
    $this->subject = null;
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
    if ($this->__parentScope) {
      return $this->__parentScope->$property;
    }
  }

  public function __set($name, $value) {
    if (is_callable($value)) {
      $this->__callables[$name] = $value;
    }
    $this->$name = $value;
  }

  public function bindCallables($scope) {
    if ($this->__parentScope) {
      $this->__parentScope->bindCallables($scope);
    }
    foreach ($this->__callables as $name => $callable) {
      $this->$name = $callable->bindTo($scope, $scope);
    }
  }

  public function isRootScope() {
    return is_null($this->__parentScope);
  }

  public function debug() {
    return $this->__debugLabel;
  }

  // Intentionally empty to allow extension
  public function beforeCallback() {}
  public function afterCallback() {}
}
