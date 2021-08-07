<?php

use Speckl\TestFailure;
use Speckl\Expectation;

function describe($label, callable $body) {
  $block = new $GLOBALS['speckl']['blockClass'](
    $label,
    $body,
    $GLOBALS['speckl']['currentBlock'],
    $GLOBALS['speckl']['currentPath']
  );
  $GLOBALS['speckl']['currentBlock'] = $block;
  echo $block->labelWithIndent();
  $block->callBody();
  $GLOBALS['speckl']['currentBlock'] = $block->parent;
}

function scenario($label, callable $body) {
  describe($label, $body);
}

function context($label, callable $body) {
  describe($label, $body);
}

function it($label, callable $body) {
  $block = new $GLOBALS['speckl']['blockClass'](
    $label,
    $body,
    $GLOBALS['speckl']['currentBlock'],
    $GLOBALS['speckl']['currentPath']
  );
  $GLOBALS['speckl']['currentBlock'] = $block;

  try {
    $block->callBeforeEachs();
    $block->callBody();
    echo "\033[32m" . $block->labelWithIndent() . "\033[0m";
  } catch (TestFailure $failure) {
    echo "\033[01;31m" . $block->labelWithIndent() . "\033[0m";
  } finally {
    $block->callAfterEachs();
  }
  $GLOBALS['speckl']['currentBlock'] = $block->parent;
}

function xit($label, callable $body) {
  $block = new $GLOBALS['speckl']['blockClass'](
    $label,
    $body,
    $GLOBALS['speckl']['currentBlock'],
    $GLOBALS['speckl']['currentPath'],
    true
  );
  echo "\033[33m" . $block->labelWithIndent() . "\033[0m";
}

function expect($expectedValue) {
  return new Expectation($expectedValue);
}

function beforeEach(callable $body) {
  $GLOBALS['speckl']['currentBlock']->addBeforeEach($body);
}

function afterEach(callable $body) {
  $GLOBALS['speckl']['currentBlock']->addAfterEach($body);
}
  