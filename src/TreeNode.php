<?php

class TreeNode {
  public function __construct($parent, $body) {
    $this->parent = $parent;
    $this->body = $body;
    $this->beforeEachs = $this->parent->beforeEachs();
    $this->afterEachs = $this->parent->afterEachs();
    $this->filePath = $this->parent->filePath;
  }

  public function calculateIndentation() {
    
  }
}
