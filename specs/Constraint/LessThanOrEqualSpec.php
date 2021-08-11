<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#lessThanOrEqualTo', function() {
    it('passes when the first number is less than the other', function() {
      expect(1)->toBe->lessThanOrEqualTo(2);
    });

    it('passes when the first number is equal to the other', function() {
      expect(1)->toBe->lessThanOrEqualTo(1);
    });

    it('fails when the first number is greater than the other', function() {
      expect(2)->toNotBe->lessThanOrEqualTo(1);
    });
  });

  describe('#beLessThanOrEqualTo', function() {
    it('aliases #lessThanOrEqualTo', function() {
      expect(1)->to->beLessThanOrEqualTo(2);
    });
  });

  describe('#lte', function() {
    it('aliases #lessThanOrEqualTo', function() {
      expect(1)->toBe->lte(2);
    });
  });
});
