<?php

use Speckl\Config;
use Speckl\Expectation;
use Speckl\TestFailure;

function describe($label, callable $body) {
  $blockClass = Config::get('blockClass');
  $block = new $blockClass([
    'label' => $label,
    'body' => $body,
    'runner' => Config::get('runner'),
    'parentBlock' => Config::get('currentBlock'),
    'path' => Config::get('currentPath')
  ]);
  Config::set('currentBlock', $block);
  echo $block->indentedLabel();
  $block->callBody();
  Config::set('currentBlock', $block->parentBlock);
}

function scenario($label, callable $body) {
  describe($label, $body);
}

function context($label, callable $body) {
  describe($label, $body);
}

function it($label, callable $body) {
  $blockClass = Config::get('blockClass');
  $block = new $blockClass([
    'label' => $label,
    'body' => $body,
    'parentBlock' => Config::get('currentBlock'),
    'path' => Config::get('currentPath')
  ]);
  Config::set('currentBlock', $block);

  try {
    $block->callBeforeEachs();
    $block->callBody();
    echo "\033[32m" . $block->indentedLabel() . "\033[0m";
  } catch (TestFailure $failure) {
    echo "\033[01;31m" . $block->indentedLabel() . "\033[0m";
  } finally {
    $block->callAfterEachs();
  }
  Config::set('currentBlock', $block->parentBlock);
}

function xit($label, callable $body) {
  $blockClass = Config::get('blockClass');
  $block = new $blockClass([
    'label' => $label,
    'body' => $body,
    'parentBlock' => Config::get('currentBlock'),
    'path' => Config::get('currentPath'),
    'pending' => true
  ]);
  echo "\033[33m" . $block->indentedLabel() . "\033[0m";
}

function expect($expectedValue) {
  return new Expectation($expectedValue);
}

function beforeEach(callable $body) {
  Config::get('currentBlock')->addBeforeEach($body);
}

function afterEach(callable $body) {
  Config::get('currentBlock')->addAfterEach($body);
}
  