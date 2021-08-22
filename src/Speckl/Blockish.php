<?php

namespace Speckl;

use ReflectionFunction;

trait Blockish {
  public $childBlocks,
         $parentBlock,
         $scope,
         $beforeCallbacks,
         $afterCallbacks,
         $lineNumbers,
         $body,
         $options,
         $pending;

  public function __construct($args) {
    $this->label = $args['label'];
    $this->pending = array_key_exists('pending', $args) ? $args['pending'] : false;
    $this->lineNumbers = $args['lineNumbers'];

    $this->childBlocks = [];
    $this->parentBlock = $args['parentBlock'];
    $this->beforeCallbacks = $this->parentBlock->beforeCallbacks ? $this->parentBlock->beforeCallbacks : [];
    $this->afterCallbacks = $this->parentBlock->afterCallbacks ? $this->parentBlock->afterCallbacks : [];
    $this->body = $args['body'];
    $this->bodyData = new ReflectionFunction($this->body);
    $this->identifier = new BlockIdentifier($this);
    $this->options = $args['options'];
  }

  public function id() {
    return $this->identifier->id();
  }

  public function namespacedId() {
    return $this->identifier->namespacedId();
  }

  public function setupScope() {
    if ($this->scope) { return; }
    $scopeClass = Container::get('scopeClass');
    $this->scope = new $scopeClass(
      $this,
      $this->parentBlock ? $this->parentBlock->scope : null,
      self::class . '/' . $this->label
    );
    $this->body = $this->bindScope($this->body);
  }

  public function runBody() {
    call_user_func_array($this->body, [$this->parentBlock]);
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

  public function isRootBlock() { return false; }
  public function isPending() { return $this->pending; }

  public function indentation() {
    if (!$this->parentBlock) {
      return 0;
    }
    return $this->parentBlock->indentation() + 2;
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
      if ($block->sentencePart()) {
        $output = $block->sentencePart()
          . (substr($output, 0, 1) === ',' ? '' : ' ')
          . $output;
      }
      $block = $block->parentBlock;
    }
    return ucfirst($output);
  }

  public function debug() {
    echo "[DEBUG] ------------------------------------------------------\n";
    echo "[DEBUG] " . $this->id() . "\n";
    echo "[DEBUG] ------------------------------------------------------\n";
    echo "[DEBUG] Should run: " . var_export($this->shouldRun(), true) . "\n";
    echo "[DEBUG] Selected line number: " . Container::get('selectedLineNumber') . "\n";
    echo "[DEBUG] Start line number: " . $this->startLineNumber() . "\n";
    echo "[DEBUG] End line number: " . $this->endLineNumber() . "\n";
    echo "[DEBUG] Child blocks: " . count($this->childBlocks) . "\n";
  }

  ////////////////////////////////////////////

  public function shouldRun() {
    return $this->containsSelectedLineNumber() || $this->ancestorShouldRun();
  }

  public function ancestorShouldRun() {
    return $this->parentBlock->ancestorShouldRun() || (
      $this->parentBlock->containsSelectedLineNumber() &&
     !$this->parentBlock->childContainsSelectedLineNumber()
    );
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

  public function childContainsSelectedLineNumber() {
    if (is_null($this->childContainsSelectedLineNumber)) {
      $this->childContainsSelectedLineNumber = $this->checkChildContainsSelectedLineNumber();
    }
    return $this->childContainsSelectedLineNumber;
  }

  public function checkChildContainsSelectedLineNumber() {
    foreach ($this->childBlocks as $childBlock) {
      if ($childBlock->containsSelectedLineNumber()) {
        return true;
      }
    }
    return false;
  }
}
