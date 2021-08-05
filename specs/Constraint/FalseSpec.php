<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#false', function() {
    it('passes for false', function() {
      expect(false)->toBe->false();
    });

    it('passes for negated false', function() {
      expect(true)->toNotBe->false();
    });

    it('fails for false', function() {
      expect(function() {
        expect(true)->toBe->false();
      })->to->fail();
    });

    it('fails for false', function() {
      expect(function() {
        expect(true)->toBe->false();
      })->to->fail();
    });

    it('fails for null', function() {
      expect(null)->toNotBe->false();
    });
  });

  describe('#beFalse', function() {
    it('passes for false', function() {
      expect(false)->to->beFalse();
    });

    it('passes for negated false', function() {
      expect(true)->toNot->beFalse();
    });
  });
});
