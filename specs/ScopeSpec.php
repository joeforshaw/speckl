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

  context('when a existing scope variable is redefined', function() {
    $this->propertyInScope = 'This a property redefined within this scope';

    it('reflects the new value', function() {
      expect($this->boundExampleCallableProperty())->to->equal('This a property redefined within this scope');
    });
  });
});
