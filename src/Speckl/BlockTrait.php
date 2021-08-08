<?php

namespace Speckl;

trait BlockTrait {
  public $path,
         $childBlocks,
         $parentBlock,
         $scope;

  private $type,
          $beforeEachs,
          $afterEachs,
          $runner,
          $body,
          $pending;

  public function initialise($args) {
    $this->type = $args['type'];
    $this->label = $args['label'];
    $this->childBlocks = [];
    $this->setupRelatedBlocks($args['parentBlock']);
    $this->scope = new Scope($this->parentBlock ? $this->parentBlock->scope : null);
    $this->body = $args['body']->bindTo($this->scope);
    $this->beforeEachs = $this->parentBlock ? $this->parentBlock->beforeEachs : [];
    $this->afterEachs = $this->parentBlock ? $this->parentBlock->afterEachs : [];
    $this->path = $args['path'];
    $this->pending = !!$args['pending'];
    if ($this->isRootBlock()) {
      $this->runner = $args['runner'];
      $this->runner->registerBlock($this);
    }
  }

  public function callBody() {
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

  public function labelColorCode() {
    if ($this->isPending()) {
      return "\033[33m" ; // Yellow
    }
    return "\033[32m"; // Green
  }

  public function addBeforeEach($beforeEach) {
    array_push($this->beforeEachs, $beforeEach);
  }

  public function callBeforeEachs() {
    foreach ($this->beforeEachs as $beforeEach) {
      $beforeEach();
    }
  }

  public function addAfterEach($afterEach) {
    array_push($this->afterEachs, $afterEach);
  }

  public function callAfterEachs() {
    foreach ($this->afterEachs as $afterEach) {
      $afterEach();
    }
  }

  public function isRootBlock() {
    return is_null($this->parentBlock);
  }

  public function isPending() {
    return $this->pending;
  }

  protected function indentation() {
    if (!$this->parentBlock) {
      return 0;
    }
    return $this->parentBlock->indentation() + 2;
  }
}
