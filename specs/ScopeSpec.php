<?php

describe('Scope', function() {
  $this->exampleCallableProperty = function() {
    return 'Callable property output';
  };
  $this->propertyInScope = 'This is a property defined within this scope';
  $this->boundExampleCallableProperty = function() {
    return $this->propertyInScope;
  };

  it('can run callable properties defined in scope', function() {
    expect($this->exampleCallableProperty())->to->equal('Callable property output');
  });

  context('when an existing scope variable is redefined within a child scope', function() {
    $this->propertyInScope = 'This is a property redefined within a child scope';

    it('reflects the value set in the child scope', function() {
      expect($this->boundExampleCallableProperty())->to->equal('This is a property redefined within a child scope');
    });

    context('when an existing scope variable is redefined within an example', function() {
      it('reflects the value set in the example', function() {
        $this->propertyInScope = 'This is a property redefined within an example';
        expect($this->boundExampleCallableProperty())->to->equal('This is a property redefined within an example');
      });
    });
  });
});
