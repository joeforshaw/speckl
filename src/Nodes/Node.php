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

    if ($this->parent) {
      $this->beforeEachs = $this->parent->beforeEachs;
      $this->afterEachs = $this->parent->afterEachs;
      $this->filePath = $this->parent->filePath;
    }
  }

  public function labelWithIndent() {
    $output = '';
    for ($i = 0; $i < $this->indentation(); $i++) {
      $output .= ' ';
    }
    return $output . $this->label;
  }

  public function call() {
    $body = $this->body;
    $body();
  }

  protected function indentation() {
    if (!$this->parent) { return 0; }
    return $this->parent->indentation() + 2;
  }
}
