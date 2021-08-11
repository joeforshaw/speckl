<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#aString', function() {
    it('passes for a string', function() {
      expect('This is a string')->toBe->aString();
    });

    it('passes for falsey string', function() {
      expect('0')->toBe->aString();
    });

    it('fails for not a string', function() {
      expect(123)->toNotBe->aString();
    });

    it('fails for null', function() {
      expect(null)->toNotBe->aString();
    });
  });

  describe('#beAString', function() {
    it('is an alias for #aString', function() {
      expect('This is a string')->to->beAString();
    });
  });

  describe('#string', function() {
    it('is an alias for #aString', function() {
      expect('This is a string')->toBeA->string();
    });
  });
});
