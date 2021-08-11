<?php

use Speckl\Constraint;
use Speckl\Exceptions\Failure;

describe(Constraint::class, function() {
  describe('#fail', function() {
    it('passes when a Failure is thrown', function() {
      expect(function() { throw new Failure(); })->to->fail();
    });

    it('passes when a expectation fails', function() {
      expect(function() { expect(true)->to->beFalse(); })->to->fail();
    });

    it("fails when all expectations pass", function() {
      expect(function() { expect(true)->to->beTrue(); })->toNot->fail();
    });
  });
});
