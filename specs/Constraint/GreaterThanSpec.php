<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#greaterThan', function() {
    it('passes for when the first number is bigger than the other', function() {
      expect(2)->toBe->greaterThan(1);
    });

    it('fails when the first number is equal to the other', function() {
      expect(1)->toNotBe->greaterThan(1);
    });

    it('fails when the first number is less than the other', function() {
      expect(1)->toNotBe->greaterThan(2);
    });
  });

  describe('#beGreaterThan', function() {
    it('aliases #greaterThan', function() {
      expect(2)->to->beGreaterThan(1);
    });
  });
});
