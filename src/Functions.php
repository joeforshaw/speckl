<?php

use Speckl\TestFailure;
use Speckl\Expectation;
use Speckl\Block;

function describe($label, $body) {
  $block = new Block(
    $label,
    $body,
    $GLOBALS['speckl']['currentBlock'],
    $GLOBALS['speckl']['currentPath']
  );
  $GLOBALS['speckl']['currentBlock'] = $block;
  echo $block->labelWithIndent();
  $block->call();
  $GLOBALS['speckl']['currentBlock'] = $block->parent;
}

function context($label, $body) {
  describe($label, $body);
}

function it($label, $body) {
  $block = new Block(
    $label,
    $body,
    $GLOBALS['speckl']['currentBlock'],
    $GLOBALS['speckl']['currentPath']
  );
  $GLOBALS['speckl']['currentBlock'] = $block;

  try {
    $block->callBeforeEachs();
    $block->call();
    echo $block->labelWithIndent();
  } catch (TestFailure $failure) {
    echo "\033[01;31m" . $block->labelWithIndent() . "\033[0m";
  } finally {
    $block->callAfterEachs();
  }
  $GLOBALS['speckl']['currentBlock'] = $block->parent;
}

function expect($expectedValue) {
  return new Expectation($expectedValue);
}

function beforeEach($body) {
  $GLOBALS['speckl']['currentBlock']->addBeforeEach($body);
}

function afterEach($body) {
  $GLOBALS['speckl']['currentBlock']->addAfterEach($body);
}
  