<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#', function() {
    xit('passes for', function() {
      expect('')->to->fail();
    });

    xit('fails for ', function() {
      expect('')->toNot->fail();
    });
  });

  describe('#alias', function() {
    xit('is an alias for #', function() {
      expect(null)->to->fail();
    });
  });
});
