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
  echo $node->labelWithIndent() . "\n";
  $node->call();
  $GLOBALS['speckl']['currentNode'] = $node->parent;
}

function context($label, $body) {
  describe($label, $body);
}

function it($label, $body) {
  $node = new It($label, $body, specklVar('currentNode'));
  $GLOBALS['speckl']['currentNode'] = $node;
  echo $node->labelWithIndent();

  try {
    $node->call();
    echo " ✅";
  } catch (TestFailure $failure) {
    echo " ❌";
  } finally {
    echo "\n";
  }
  $GLOBALS['speckl']['currentNode'] = $node->parent;
}

function expect($expectedValue) {
  return new Expectation($expectedValue);
}

function beforeEach($body) {
  $body();
}
  