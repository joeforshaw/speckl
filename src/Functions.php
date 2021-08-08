<?php

use Speckl\Config;
use Speckl\Expectation;
use Speckl\TestFailure;

function group($type, $label, callable $body) {
  $blockClass = Config::get('blockClass');
  $block = new $blockClass([
    'type' => $type,
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

function describe($label, callable $body) {
  group('describe', $label, $body);
}

function scenario($label, callable $body) {
  group('scenario', $label, $body);
}

function context($label, callable $body) {
  group('context', $label, $body);
}

function example($args) {
  $blockClass = Config::get('blockClass');
  $block = new $blockClass(array_merge($args, [
    'parentBlock' => Config::get('currentBlock'),
    'path' => Config::get('currentPath')
  ]));
  Config::set('currentBlock', $block);

  try {
    $block->callBeforeEachs();
    if (!$block->isPending()) {
      $block->callBody();
    }
    echo $block->labelColorCode() . $block->indentedLabel() . "\033[0m";
  } catch (TestFailure $failure) {
    echo "\033[01;31m" . $block->indentedLabel() . "\033[0m";
  } finally {
    $block->callAfterEachs();
  }
  Config::set('currentBlock', $block->parentBlock);
}

function it($label, callable $body) {
  example([
    'type' => 'it',
    'label' => $label,
    'body' => $body
  ]);
}

function xit($label, callable $body) {
  example([
    'type' => 'it',
    'label' => $label,
    'body' => $body,
    'pending' => true
  ]);
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
