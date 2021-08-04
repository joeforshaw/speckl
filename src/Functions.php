<?php

use Speckl\TestFailure;
use Speckl\Expectation;
use Speckl\Nodes\Describe;
use Speckl\Nodes\It;

$speckl = [
  'indentation' => 0,
  'currentNode' => null,
];

function specklVar($variableName) {
  return $GLOBALS['speckl'][$variableName];
}

function describe($label, $body) {
  $node = new Describe($label, $body, specklVar('currentNode'));
  $GLOBALS['speckl']['currentNode'] = $node;
  echo $node->labelWithIndent();
  $node->call();
  $GLOBALS['speckl']['currentNode'] = $node->parent;
}

function context($label, $body) {
  describe($label, $body);
}

function it($label, $body) {
  $node = new It($label, $body, specklVar('currentNode'));
  $GLOBALS['speckl']['currentNode'] = $node;

  try {
    $node->callBeforeEachs();
    $node->call();
    echo $node->labelWithIndent();
  } catch (TestFailure $failure) {
    echo "\033[01;31m" . $node->labelWithIndent() . "\033[0m";
  } finally {
    $node->callAfterEachs();
  }
  $GLOBALS['speckl']['currentNode'] = $node->parent;
}

function expect($expectedValue) {
  return new Expectation($expectedValue);
}

function beforeEach($body) {
  $GLOBALS['speckl']['currentNode']->addBeforeEach($body);
}

function afterEach($body) {
  $GLOBALS['speckl']['currentNode']->addAfterEach($body);
}
  