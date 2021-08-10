<?php

describe('a context block', function() {
  context('<this is a context block>', function() {
    it('nests the it label underneath it', function() {
      expect(true)->to->beTrue();
    });
  });
});
