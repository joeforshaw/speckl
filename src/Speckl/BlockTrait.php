<?php

namespace Speckl;

trait BlockTrait {
  public $path,
         $childBlocks,
         $parentBlock,
         $scope;

  private $beforeEachs,
          $afterEachs,
          $runner,
          $body,
          $pending;

  public function initialise($args) {
    $this->label = $args['label'];
    $this->runner = $args['runner'];
    $this->childBlocks = [];
    $this->addParentBlock($args['parentBlock']);
    $this->scope = new Scope($this->parentBlock ? $this->parentBlock->scope : null);
    $this->body = $args['body']->bindTo($this->scope);
    $this->beforeEachs = $this->parentBlock ? $this->parentBlock->beforeEachs : [];
    $this->afterEachs = $this->parentBlock ? $this->parentBlock->afterEachs : [];
    $this->path = $args['path'];
    $this->pending = !!$args['pending'];
  }

  public function callBody() {
    call_user_func($this->body);
  }

  public function addParentBlock($parentBlock) {
    $this->parentBlock = $parentBlock;
    if ($this->parentBlock) {
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

  protected function indentation() {
    if (!$this->parentBlock) {
      return 0;
    }
    return $this->parentBlock->indentation() + 2;
  }
}
