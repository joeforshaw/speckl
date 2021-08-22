<?php

describe('A sharedContext block', function() {
  context('when defined in the same file', function() {
    includeContext('shared context from the same file');

    it('allows variables to be shared between blocks', function() {
      expect($this->sharedContextVariable)->to->equal('My shared context variable in the same file');
    });
  });

  context('when defined in another file', function() {
    includeContext('shared context from another file');

    it('allows variables to be shared between blocks', function() {
      expect($this->sharedContextVariable)->to->equal('My shared context variable from another file');
    });
  });

  context('when variables are before an example in the same scope', function() {
    includeContext('shared context from the same file');

    $this->sharedContextVariable = "It's changed!";

    it("it should see the changed value", function() {      
      expect($this->sharedContextVariable)->to->equal("It's changed!");
    });
  });

  context('when variables are changed after an example in the same scope', function() {
    includeContext('shared context from the same file');
    
    it("doesn't see the changed value", function() {
      expect($this->sharedContextVariable)->to->equal('My shared context variable in the same file');
    });

    $this->sharedContextVariable = "It's changed!";
  });
});

sharedContext('shared context from the same file', function() {
  $this->sharedContextVariable = 'My shared context variable in the same file';
});
