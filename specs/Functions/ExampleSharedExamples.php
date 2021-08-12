<?php

sharedExamples('shared examples from another file', function() {
  it('can run examples when included', function() {
    expect(true)->to->beTrue();
  });

  context('within a context block', function() {
    it('can run examples when included', function() {
      expect(true)->to->beTrue();
    });
  });
});
