<?php

namespace Speckl;

use ReflectionFunction;

trait Blockish {
  public $childBlocks,
         $parentBlock,
         $scope,
         $lazy,
         $beforeCallbacks,
         $afterCallbacks,
         $sharedContexts,
         $lineNumbers;

  private $body,
          $pending;

  public function __construct($args) {
    $this->label = $args['label'];
    $this->pending = array_key_exists('pending', $args) ? $args['pending'] : false;
    $this->lineNumbers = $args['lineNumbers'];

    $this->childBlocks = [];
    $this->parentBlock = $args['parentBlock'];
    $this->beforeCallbacks = $this->parentBlock ? $this->parentBlock->beforeCallbacks : [];
    $this->afterCallbacks = $this->parentBlock ? $this->parentBlock->afterCallbacks : [];
    $this->sharedContexts = [];
    $this->scope = $this->setupScope(Container::get('scopeClass'));
    $this->body = $this->bindScope($args['body']);
    $this->bodyData = new ReflectionFunction($this->body);
    $this->lazy = $args['lazy'];
  }

  public function setupScope($scopeClass) {
    return new $scopeClass(
      self::class . '/' . $this->label,
      $this->parentBlock ? $this->parentBlock->scope : null
    );
  }

  public function runBody($block = null) {
    call_user_func_array($this->body, [$block]);
  }

  public function addChildBlock($childBlock) {
    array_push($this->childBlocks, $childBlock);
  }

  public function bindScope($body) {
    return $body->bindTo($this->scope, $this->scope);
  }

  public function indentationString() {
    $output = '';
    for ($i = 0; $i < $this->indentation(); $i++) {
      $output .= ' ';
    }
    return $output;
  }

  public function indentedLabel($colour = null) {
    $output = $this->indentationString();
    $output .= $this->label;
    if ($this->pending) {
      $output .= ' (pending)';
    }
    if ($colour) {
      $output = $colour . $output . "\033[0m";
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

  public function isLazy() {
    return false;
  }

  public function resolveNextLazyBlock() {
    foreach ($this->childBlocks as $i => $block) {
      if (!$block->lazy) { continue; }
      $block->resolveLazyBlock($i);
      return true;
    }
    return false;
  }

  public function resolveLazyBlock($i) {
    Container::set('currentBlock', $this);
    $this->runBody($this->parentBlock);
    Container::set('currentBlock', $this->parentBlock);
    foreach ($this->childBlocks as $block) {
      $block->parentBlock = $this->parentBlock;
    }
    // Remove the placeholder shared example block
    unset($this->parentBlock->childBlocks[$i]);

    // Move the shared example child blocks to the shared example's parent
    $this->childBlocks = array_splice(
      $this->parentBlock->childBlocks, $i, 0, $this->childBlocks
    );
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

  public function filePath() {
    return substr(
      $this->bodyData->getFileName(),
      strlen(getcwd()) + 1 // +1 removes the slash at the start
    );
  }

  public function startLineNumber() {
    return $this->bodyData->getStartLine();
  }

  public function endLineNumber() {
    return $this->bodyData->getEndLine();
  }

  public function sentencePart() {
    return $this->label;
  }

  public function sentence() {
    $block = $this->parentBlock;
    $output = $this->label;
    while(!is_null($block)) {
      $output = $block->sentencePart()
        . (substr($output, 0, 1) === ',' ? '' : ' ')
        . $output;
      $block = $block->parentBlock;
    }
    return ucfirst($output);
  }

  public function selectedLineNumberSet() {
    return Container::exists('selectedLineNumber');
  }

  public function containsSelectedLineNumber() {
    return !$this->selectedLineNumberSet() || (
      Container::get('selectedLineNumber') >= $this->startLineNumber() &&
      Container::get('selectedLineNumber') <= $this->endLineNumber()
    );
  }
}
