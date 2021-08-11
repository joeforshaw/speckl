<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#beEmpty', function() {
    it('passes for an empty array', function() {
      expect([])->toBe->beEmpty();
    });

    it('passes for an empty string', function() {
      expect('')->toBe->beEmpty();
    });

    it('passes for null', function() {
      expect(null)->toBe->beEmpty();
    });

    it('fails for an array with items', function() {
      expect(['An item'])->toNotBe->beEmpty();
    });

    it('fails for a non-empty string', function() {
      expect('A non-empty string')->toNotBe->beEmpty();
    });
  });
});
