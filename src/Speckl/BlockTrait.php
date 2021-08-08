<?php

namespace Speckl;

trait BlockTrait {
  public $path,
         $childBlocks,
         $parentBlock,
         $scope;

  private $type,
          $beforeCallbacks,
          $afterCallbacks,
          $runner,
          $body,
          $pending;

  public function initialise($args) {
    $this->type = $args['type'];
    $this->label = $args['label'];
    $this->childBlocks = [];
    $this->setupRelatedBlocks($args['parentBlock']);
    $this->scope = new Scope($this->parentBlock ? $this->parentBlock->scope : null);
    $this->body = $this->bindScope($args['body']);
    $this->beforeCallbacks = $this->parentBlock ? $this->parentBlock->beforeCallbacks : [];
    $this->afterCallbacks = $this->parentBlock ? $this->parentBlock->afterCallbacks : [];
    $this->path = $args['path'];
    $this->pending = !!$args['pending'];
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
    return $body->bindTo($this->scope);
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
    array_push($this->afterCallbacks, $afterCallback);
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
