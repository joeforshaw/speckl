<?php

use Speckl\Container;
use Speckl\Context;
use Speckl\Describe;
use Speckl\Expectation;
use Speckl\It;
use Speckl\Scenario;
use Speckl\SharedExamples;

function group($class, $args) {
  $args = array_merge($args, [
    'parentBlock' => Container::get('currentBlock'),
  ]);
  $block = new $class($args);
  Container::set('currentBlock', $block);
  $block->loadBlock();
  Container::set('currentBlock', $block->parentBlock);
}

function example($class, $args) {
  $args = array_merge($args, [
    'parentBlock' => Container::get('currentBlock'),
  ]);
  $block = new $class($args);
  $block->loadBlock();
}

function describe($label, callable $body) {
  group(Describe::class, [
    'label' => $label,
    'body' => $body,
  ]);
}

function context($label, callable $body) {
  group(Context::class, [
    'label' => $label,
    'body' => $body,
  ]);
}

function it($label, callable $body) {
  example(It::class, [
    'label' => $label,
    'body' => $body,
  ]);
}

function xit($label, callable $body) {
  example(It::class, [
    'label' => $label,
    'body' => $body,
    'pending' => true
  ]);
}

function scenario($label, callable $body) {
  example(Scenario::class, [
    'label' => $label,
    'body' => $body,
  ]);
}

function expect($expectedValue) {
  $lineNumber = debug_backtrace()[0]['line'];
  return new Expectation($expectedValue, $lineNumber);
}

function beforeEach(callable $body) {
  Container::get('currentBlock')->addBeforeCallback($body);
}

function afterEach(callable $body) {
  Container::get('currentBlock')->addAfterCallback($body);
}

function sharedContext($label, callable $body) {
  Container::get('runner')->addSharedContext($label, $body);
}

function includeContext($label) {
  $currentBlock = Container::get('currentBlock');
  $currentBlock->addSharedContext(function($block) use ($label) {
    $sharedContext = Container::get('runner')->getSharedContext($label);
    $sharedContext = $block->bindScope($sharedContext);
    call_user_func_array($sharedContext, [$block]);
  });
}

function sharedExamples($label, callable $body) {
  Container::get('runner')->addSharedExamples($label, $body);
}

function includeExamples($label) {
  group(SharedExamples::class, [
    'label' => $label,
    'lazy' => true,
    'body' => function() use ($label) {
      $sharedExamples = Container::get('runner')->getSharedExamples($label);
      call_user_func($sharedExamples);
    },
  ]);
}
function itBehavesLike($label) { includeExamples($label); }
