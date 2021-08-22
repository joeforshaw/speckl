<?php

use Speckl\Container;
use Speckl\Context;
use Speckl\Describe;
use Speckl\Expectation;
use Speckl\It;
use Speckl\Scenario;

function group($class, $args) {
  if (Container::get('loading')) { return; } 
  $args['parentBlock'] = Container::get('currentBlock');
  $block = new $class($args);
  Container::set('currentBlock', $block);
  Container::get('runner')->loadBlock($block);
  $block->runBlock();
  Container::set('currentBlock', $args['parentBlock']);
}

function example($class, $args) {
  if (Container::get('loading')) { return; }
  $args['parentBlock'] = Container::get('currentBlock');
  $block = new $class($args);
  Container::get('runner')->loadBlock($block);
  $block->setupScope();
  $block->runBlock();
}

function describe($label, callable $body) {
  group(Describe::class, [ 'label' => $label, 'body' => $body ]);
}

function context($label, callable $body) {
  group(Context::class, [ 'label' => $label, 'body' => $body ]);
}

function it($label, callable $body) {
  example(It::class, [ 'label' => $label, 'body' => $body ]);
}

function xit($label, callable $body) {
  example(It::class, [
    'label' => $label,
    'body' => $body,
    'pending' => true
  ]);
}

function scenario($label, callable $body) {
  example(Scenario::class, [ 'label' => $label, 'body' => $body ]);
}

function expect($expectedValue) {
  $lineNumber = debug_backtrace()[0]['line'];
  return new Expectation($expectedValue, $lineNumber);
}

function beforeEach(callable $body) {
  if (Container::get('loading')) { return; }
  Container::get('currentBlock')->addBeforeCallback($body);
}

function afterEach(callable $body) {
  if (Container::get('loading')) { return; }
  Container::get('currentBlock')->addAfterCallback($body);
}

function shareBlock($label, callable $body) {
  Container::get('runner')->addSharedBlock($label, $body);
}
function sharedContext($label, callable $body) { shareBlock($label, $body); }
function sharedExamples($label, callable $body) { shareBlock($label, $body); }
function sharedExamplesFor($label, callable $body) { shareBlock($label, $body); }
function behaviorOf($label, callable $body) { shareBlock($label, $body); }

function includeSharedBlock($label) {
  if (Container::get('loading')) { return; }
  $block = Container::get('currentBlock');
  $sharedBlock = Container::get('runner')->getSharedBlock($label);
  $block->setupScope();
  call_user_func($block->bindScope($sharedBlock));
}
function includeContext($label) { includeSharedBlock($label); }
function includeExamples($label) { includeSharedBlock($label); }
function itBehavesLike($label) { includeSharedBlock($label); }
