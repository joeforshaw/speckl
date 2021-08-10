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
});

sharedContext('shared context from the same file', function() {
  $this->sharedContextVariable = 'My shared context variable in the same file';
});
