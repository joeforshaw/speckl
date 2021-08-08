<?php

describe('Scope', function() {
  $this->exampleCallableProperty = function() {
    return 'Callable property output';
  };
  $this->propertyInScope = 'This a property defined within this scope';
  $this->boundExampleCallableProperty = function() {
    return $this->propertyInScope;
  };

  it('can run callable properties defined in scope', function() {
    expect($this->exampleCallableProperty())->to->equal('Callable property output');
  });

  it('binds itself to callable properties', function() {
    expect($this->boundExampleCallableProperty())->to->equal('This a property defined within this scope');
  });
});
