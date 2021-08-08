<?php

namespace Speckl;

abstract class Block {
  public $path,
         $childBlocks,
         $parentBlock,
         $scope,
         $beforeCallbacks,
         $afterCallbacks;

  private $type,
          $body,
          $pending;

  public function __construct($args) {
    $this->type = $args['type'];
    $this->label = $args['label'];
    $this->path = $args['path'];
    $this->pending = array_key_exists('pending', $args) ? $args['pending'] : false;

    $this->childBlocks = [];
    $this->setupRelatedBlocks($args['parentBlock']);
    $this->beforeCallbacks = $this->parentBlock ? $this->parentBlock->beforeCallbacks : [];
    $this->afterCallbacks = $this->parentBlock ? $this->parentBlock->afterCallbacks : [];
    $scopeClass = Config::get('scopeClass');
    $this->scope = new $scopeClass($this->parentBlock ? $this->parentBlock->scope : null);
    $this->body = $this->bindScope($args['body']);
  }

  public function runBody() {
    call_user_func($this->body);
  }

  public function setupRelatedBlocks($parentBlock) {
    $this->parentBlock = $parentBlock;
    if (!$this->isRootBlock()) {
      $this->parentBlock->addChildBlock($this);
    }
  }

  public function addChildBlock($childBlock) {
    array_push($this->childBlocks, $childBlock);
  }

  public function bindScope($body) {
    return $body->bindTo($this->scope, $this->scope);
  }

  public function indentedLabel() {
    $output = '';
    for ($i = 0; $i < $this->indentation(); $i++) {
      $output .= ' ';
    }
    $output .= $this->label;
    if ($this->pending) {
      $output .= ' (pending)';
    }
    return $output . "\n";
  }

  public function addBeforeCallback($beforeCallback) {
    array_push($this->beforeCallbacks, $beforeCallback);
  }

  public function runBeforeCallbacks() {
    foreach ($this->beforeCallbacks as $beforeCallback) {
      $beforeCallback();
    }
  }

  public function addAfterCallback($afterCallback) {
    array_unshift($this->afterCallbacks, $afterCallback);
  }

  public function runAfterCallbacks() {
    foreach ($this->afterCallbacks as $afterCallback) {
      $afterCallback();
    }
  }

  public function isRootBlock() {
    return is_null($this->parentBlock);
  }

  public function isPending() {
    return $this->pending;
  }

  public function indentation() {
    if (!$this->parentBlock) {
      return 0;
    }
    return $this->parentBlock->indentation() + 2;
  }
}
