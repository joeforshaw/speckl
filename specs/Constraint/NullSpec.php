<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#null', function() {
    it('passes for null', function() {
      expect(null)->toBe->null();
    });

    it('fails for not null', function() {
      expect('This is not null')->toNotBe->null();
    });

    it('fails for falsey', function() {
      expect(false)->toNotBe->null();
    });
  });

  describe('#beNull', function() {
    it('is an alias for #null', function() {
      expect(null)->to->beNull();
    });
  });
});
