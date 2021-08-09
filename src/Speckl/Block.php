<?php

namespace Speckl;

abstract class Block {
  public $path,
         $childBlocks,
         $parentBlock,
         $scope,
         $beforeCallbacks,
         $afterCallbacks,
         $sharedContexts;

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
    $this->sharedContexts = [];
    $this->scope = $this->setupScope(Config::get('scopeClass'));
    $this->body = $this->bindScope($args['body']);
  }

  public function setupScope($scopeClass) {
    return new $scopeClass(
      $this->type . '/' . $this->label,
      $this->parentBlock ? $this->parentBlock->scope : null
    );
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

  public function prependBeforeCallback($beforeCallback) {
    array_unshift($this->beforeCallbacks, $beforeCallback);
  }

  public function runBeforeCallbacks() {
    foreach ($this->beforeCallbacks as $beforeCallback) {
      $beforeCallback = $this->bindScope($beforeCallback);
      call_user_func($beforeCallback);
    }
  }

  public function addAfterCallback($afterCallback) {
    array_unshift($this->afterCallbacks, $afterCallback);
  }

  public function runAfterCallbacks() {
    foreach ($this->afterCallbacks as $afterCallback) {
      $afterCallback = $this->bindScope($afterCallback);
      call_user_func($afterCallback);
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

  public function addSharedContext(callable $sharedContext) {
    array_push($this->sharedContexts, $sharedContext);
  }

  public function runSharedContexts($block) {
    if ($this->parentBlock) {
      $this->parentBlock->runSharedContexts($block);
    }
    foreach ($this->sharedContexts as $sharedContext) {
      $sharedContext = $block->bindScope($sharedContext);
      call_user_func_array($sharedContext, [$block]);
    }
  }
}
