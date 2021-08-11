<?php

namespace Speckl;

use ReflectionFunction;

abstract class Block {
  public $childBlocks,
         $parentBlock,
         $scope,
         $beforeCallbacks,
         $afterCallbacks,
         $sharedContexts,
         $lineNumbers;

  private $type,
          $body,
          $pending;

  public function __construct($args) {
    $this->type = $args['type'];
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
    if ($this->type === 'context') {
      return ', ' . $this->label . ',';
    }
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
