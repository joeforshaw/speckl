<?php

sharedContext('example sharedContext', function() {
  $this->sharedContextVariable = 'My shared context variable';
});

describe('a sharedContext block', function() {
  includeContext('example sharedContext');

  it('allows variables to be shared', function() {
    expect($this->sharedContextVariable)->to->equal('My shared context variable');
  });
});
