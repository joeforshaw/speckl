<?php

namespace Speckl\Nodes;

abstract class Node {
  protected $beforeEachs,
            $afterEachs,
            $filePath;

  public function __construct($label, $body, $parent) {
    $this->label = $label;
    $this->body = $body;
    $this->parent = $parent;

    $this->beforeEachs = $this->parent ? $this->parent->beforeEachs : [];
    $this->afterEachs = $this->parent ? $this->parent->afterEachs : [];
    $this->filePath = $this->parent ? $this->parent->filePath : null;
  }

  public function call() {
    $body = $this->body;
    $body();
  }

  public function labelWithIndent() {
    $output = '';
    for ($i = 0; $i < $this->indentation(); $i++) {
      $output .= ' ';
    }
    return $output . $this->label . "\n";
  }

  public function addBeforeEach($beforeEach) {
    array_push($this->beforeEachs, $beforeEach);
  }

  public function callBeforeEachs() {
    foreach ($this->beforeEachs as $beforeEach) {
      $beforeEach();
    }
  }

  public function addAfterEach($afterEach) {
    array_push($this->afterEachs, $afterEach);
  }

  public function callAfterEachs() {
    foreach ($this->afterEachs as $afterEach) {
      $afterEach();
    }
  }

  protected function indentation() {
    if (!$this->parent) { return 0; }
    return $this->parent->indentation() + 2;
  }
}
