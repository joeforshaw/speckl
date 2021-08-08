<?php

describe('Scope', function() {
  $this->exampleCallableProperty = function() {
    return 'Callable property output';
  };

  it('can run callable properties defined in scope', function() {
    expect($this->exampleCallableProperty())->to->equal('Callable property output');
  });
});
