<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#lessThan', function() {
    it('passes for when the first number is less than the other', function() {
      expect(1)->toBe->lessThan(2);
    });

    it('fails when the first number is equal to the other', function() {
      expect(1)->toNotBe->lessThan(1);
    });

    it('fails when the first number is greater than the other', function() {
      expect(2)->toNotBe->lessThan(1);
    });
  });

  describe('#beLessThan', function() {
    it('aliases #lessThan', function() {
      expect(1)->to->beLessThan(2);
    });
  });
});
