<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#greaterThanOrEqualTo', function() {
    it('passes for when the first number is greater than the other', function() {
      expect(2)->toBe->greaterThanOrEqualTo(1);
    });

    it('passes when the first number is equal to the other', function() {
      expect(1)->toBe->greaterThanOrEqualTo(1);
    });

    it('fails when the first number is less than the other', function() {
      expect(1)->toNotBe->greaterThanOrEqualTo(2);
    });
  });

  describe('#beGreaterThanOrEqualTo', function() {
    it('aliases #greaterThanOrEqualTo', function() {
      expect(2)->to->beGreaterThanOrEqualTo(1);
    });
  });

  describe('#gte', function() {
    it('aliases #greaterThanOrEqualTo', function() {
      expect(2)->toBe->gte(1);
    });
  });
});
