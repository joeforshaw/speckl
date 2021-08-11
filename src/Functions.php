<?php

use Speckl\Container;
use Speckl\Expectation;

function group($args) {
  $args = array_merge($args, [
    'parentBlock' => Container::get('currentBlock'),
  ]);
  $groupBlockClass = Container::get('groupBlockClass');
  $block = new $groupBlockClass($args);
  Container::set('currentBlock', $block);
  $block->loadBlock();
  Container::set('currentBlock', $block->parentBlock);
}

function example($args) {
  $args = array_merge($args, [
    'parentBlock' => Container::get('currentBlock'),
  ]);
  $exampleBlockClass = Container::get('exampleBlockClass');
  $block = new $exampleBlockClass($args);
  $block->loadBlock();
}

function describe($label, callable $body) {
  group([
    'type' => 'describe',
    'label' => $label,
    'body' => $body,
  ]);
}

function context($label, callable $body) {
  group([
    'type' => 'context',
    'label' => $label,
    'body' => $body,
  ]);
}

function it($label, callable $body) {
  example([
    'type' => 'it',
    'label' => $label,
    'body' => $body,
  ]);
}

function xit($label, callable $body) {
  example([
    'type' => 'xit',
    'label' => $label,
    'body' => $body,
    'pending' => true
  ]);
}

function scenario($label, callable $body) {
  example([
    'type' => 'scenario',
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
