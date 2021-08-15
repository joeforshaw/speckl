<?php

use Speckl\Container;
use Speckl\Context;
use Speckl\Describe;
use Speckl\Expectation;
use Speckl\It;
use Speckl\Scenario;
use Speckl\SharedBlock;

function group($class, $args) {
  $args['parentBlock'] = Container::get('currentBlock');
  if (Container::get('loading')) {
    $block = new $class($args);
    Container::set('currentBlock', $block);
    Container::get('runner')->loadBlock($block);
    Container::set('currentBlock', $block->parentBlock);
  } else {
    $parentBlock = Container::get('currentBlock');
    $block = Container::get('runner')->getLoadedBlock($class, $args);
    Container::set('currentBlock', $block);
    if ($block) { $block->runBlock(); }
    Container::set('currentBlock', $parentBlock);
  }
}

function example($class, $args) {
  $args['parentBlock'] = Container::get('currentBlock');
  if (Container::get('loading')) {
    $block = new $class($args);
    Container::get('runner')->loadBlock($block);
  } else {
    $block = Container::get('runner')->getLoadedBlock($class, $args);
    if ($block) {
      $block->setupScope();
      $block->runBlock();
    }
  }
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
  if (Container::get('loading')) {
    Container::get('currentBlock')->addBeforeCallback($body);
  }
}

function afterEach(callable $body) {
  if (Container::get('loading')) {
    Container::get('currentBlock')->addAfterCallback($body);
  }
}

function shareBlock($label, callable $body) {
  Container::get('runner')->addSharedBlock($label, $body);
}
function sharedContext($label, callable $body) { shareBlock($label, $body); }
function sharedExamples($label, callable $body) { shareBlock($label, $body); }
function sharedExamplesFor($label, callable $body) { shareBlock($label, $body); }
function behaviorOf($label, callable $body) { shareBlock($label, $body); }

function includeSharedBlock($label) {
  group(SharedBlock::class, [
    'label' => $label,
    'body' => function($parentBlock) use ($label) {
      if (!Container::get('loading')) {
        $sharedBlock = Container::get('runner')->getSharedBlock($label);
        call_user_func($parentBlock->bindScope($sharedBlock));
      }
    },
  ]);
}
function includeContext($label) { includeSharedBlock($label); }
function includeExamples($label) { includeSharedBlock($label); }
function itBehavesLike($label) { includeSharedBlock($label); }
