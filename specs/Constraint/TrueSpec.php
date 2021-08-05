<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#true', function() {
    it('passes for true', function() {
      expect(true)->toBe->true();
    });

    it('passes for negated true', function() {
      expect(false)->toNotBe->true();
    });

    it('fails for false', function() {
      expect(function() {
        expect(false)->toBe->true();
      })->to->fail();
    });

    it('fails for null', function() {
      expect(null)->toNotBe->true();
    });
  });

  describe('#beTrue', function() {
    it('passes for true', function() {
      expect(true)->to->beTrue();
    });

    it('passes for negated true', function() {
      expect(false)->toNot->beTrue();
    });
  });
});
