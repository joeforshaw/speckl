<?php

describe('A context block', function() {
  context('<this is a context block>', function() {
    it('nests the it label underneath it', function() {
      expect(true)->to->beTrue();
    });
  });
});
