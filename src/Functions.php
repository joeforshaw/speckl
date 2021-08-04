<?php

$indentation = 0;
$currentNode = null;

function withIndent($message) {
  $output = '';
  for ($i = 0; $i < $GLOBALS['indentation']; $i++) {
    $output .= ' ';
  }
  return $output . $message;
}

function increaseIndent() {
  $GLOBALS['indentation'] += 2;
}

function decreaseIndent() {
  $GLOBALS['indentation'] -= 2;
}

function describe($subject, $body) {
  echo withIndent("$subject\n");
  increaseIndent();
  $body();
  decreaseIndent();
}

function context($context, $body) {
  describe($context, $body);
}

function it($description, $body) {
  echo withIndent($description);
  try {
    $body();
    echo " ✅";
  } catch (TestFailure $failure) {
    echo " ❌";
  } finally {
    echo "\n";
  }
}

function expect($expectedValue) {
  return new Expectation($expectedValue);
}

function beforeEach($body) {
  $body();
}
  