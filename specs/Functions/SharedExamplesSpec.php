<?php

sharedExamples('shared examples from the same file', function() {
  it('can run examples', function() {
    expect(true)->to->beTrue();
  });
});

describe('A sharedExamples block', function() {
  context('when defined in the same file', function() {
    includeExamples('shared examples from the same file');
  });

  context('when defined in another file', function() {
    includeExamples('shared examples from another file');
  });
});
