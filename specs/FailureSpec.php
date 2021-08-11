<?php

describe('A failed spec', function() {
  it('renders the label red', function() {
    expect(true)->to->beFalse();
  });

  it('handles exceptions', function() {
    throw new Exception('This is an intentional exception');
  });
});
