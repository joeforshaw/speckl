<?php

sharedExamples('shared examples from another file', function() {
  $this->variableInScope = 'A variable in scope';

  it('can run examples when included', function() {
    expect($this->variableInScope)->to->equal('A variable in scope');
  });

  context('within a context block', function() {
    it('can run examples when included', function() {
      expect(true)->to->beTrue();
    });
  });
});
