<?php

namespace Speckl;

trait BlockTrait
{
  public $path, $parent;

  private $beforeEachs,
          $afterEachs,
          $body,
          $scope;

  public function callBody()
  {
    call_user_func($this->body);
  }

  public function labelWithIndent()
  {
    $output = '';
    for ($i = 0; $i < $this->indentation(); $i++) {
      $output .= ' ';
    }
    return $output . $this->label . "\n";
  }

  public function addBeforeEach($beforeEach)
  {
    array_push($this->beforeEachs, $beforeEach);
  }

  public function callBeforeEachs()
  {
    foreach ($this->beforeEachs as $beforeEach) {
      $beforeEach();
    }
  }

  public function addAfterEach($afterEach)
  {
    array_push($this->afterEachs, $afterEach);
  }

  public function callAfterEachs()
  {
    foreach ($this->afterEachs as $afterEach) {
      $afterEach();
    }
  }

  protected function indentation()
  {
    if (!$this->parent) {
      return 0;
    }
    return $this->parent->indentation() + 2;
  }
}
