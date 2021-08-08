<?php

use Speckl\Config;
use Speckl\Expectation;
use Speckl\GroupBlock;

function group($args) {
  $args = array_merge($args, [
    'parentBlock' => Config::get('currentBlock'),
    'path' => Config::get('currentPath')
  ]);
  $groupBlockClass = Config::get('groupBlockClass');
  $block = new $groupBlockClass($args);
  Config::set('currentBlock', $block);
  $block->loadBlock();
  Config::set('currentBlock', $block->parentBlock);
}

function example($args) {
  $args = array_merge($args, [
    'parentBlock' => Config::get('currentBlock'),
    'path' => Config::get('currentPath')
  ]);
  $exampleBlockClass = Config::get('exampleBlockClass');
  new $exampleBlockClass($args);
}

function describe($label, callable $body) {
  group(['type' => 'describe', 'label' => $label, 'body' => $body]);
}

function context($label, callable $body) {
  group(['type' => 'context', 'label' => $label, 'body' => $body]);
}

function it($label, callable $body) {
  example(['type' => 'it', 'label' => $label, 'body' => $body]);
}

function xit($label, callable $body) {
  example([ 'type' => 'xit', 'label' => $label, 'body' => $body, 'pending' => true]);
}

function scenario($label, callable $body) {
  example(['type' => 'scenario', 'label' => $label, 'body' => $body ]);
}

function expect($expectedValue) {
  return new Expectation($expectedValue);
}

function beforeEach(callable $body) {
  Config::get('currentBlock')->addBeforeCallback($body);
}

function afterEach(callable $body) {
  Config::get('currentBlock')->addAfterCallback($body);
}

function sharedContext($label, callable $body) {
  Config::get('runner')->addSharedContext($label, $body);
}

function includeContext($label) {
  $block = Config::get('currentBlock');
  $block->addBeforeCallback(function() use ($block, $label) {
    $sharedContext = Config::get('runner')->getSharedContext($label);
    $sharedContext = $block->bindScope($sharedContext);
    call_user_func($sharedContext);
  });
}
