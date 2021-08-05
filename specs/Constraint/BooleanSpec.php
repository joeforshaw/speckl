<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#true', function() {
    it('passes for true', function() {
      expect(true)->toBe->true();
    });

    it('fails for false', function() {
      expect(function() {
        expect(false)->toBe->true();
      })->to->fail();
    });
  });

  describe('#beTrue', function() {
    it('is an alias of #true', function() {
      expect(true)->to->beTrue();
    });
  });

  describe('#false', function() {
    it('passes for false', function() {
      expect(false)->toBe->false();
    });

    it('fails for false', function() {
      expect(function() {
        expect(true)->toBe->false();
      })->to->fail();
    });
  });

  describe('#beFalse', function() {
    it('is an alias of #false', function() {
      expect(false)->to->beFalse();
    });
  });
});
