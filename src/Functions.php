<?php

use Speckl\Container;
use Speckl\Context;
use Speckl\Describe;
use Speckl\Expectation;
use Speckl\It;
use Speckl\Scenario;
use Speckl\SharedBlock;

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

function shareBlock($label, callable $body) {
  Container::get('runner')->addSharedBlock($label, $body);
}
function sharedContext($label, callable $body) { shareBlock($label, $body); }
function sharedExamples($label, callable $body) { shareBlock($label, $body); }

function includeSharedBlock($label) {
  group(SharedBlock::class, [
    'label' => $label,
    'lazy' => true,
    'body' => function($block) use ($label) {
      $sharedExamples = Container::get('runner')->getSharedExamples($label);
      call_user_func($block->bindScope($sharedExamples));
    },
  ]);
}
function includeContext($label) { includeSharedBlock($label); }
function includeExamples($label) { includeSharedBlock($label); }
function itBehavesLike($label) { includeSharedBlock($label); }
