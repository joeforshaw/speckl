<?php

namespace Speckl;

use Speckl\Scope;

class Block
{
  use BlockTrait;

  public function __construct($label, $body, $parent, $path)
  {
    $this->label = $label;
    $this->parent = $parent;
    $this->scope = new Scope($this->parent ? $this->parent->scope : null);
    $this->body = $body->bindTo($this->scope);
    $this->beforeEachs = $this->parent ? $this->parent->beforeEachs : [];
    $this->afterEachs = $this->parent ? $this->parent->afterEachs : [];
    $this->path = $path;
  }
}
