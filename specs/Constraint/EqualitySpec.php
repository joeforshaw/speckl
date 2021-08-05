<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#equal', function() {
    context('when comparing booleans', function() {
      it('passes for true', function() {
        expect(true)->to->equal(true);
      });
    
      it('passes for negated true', function() {
        expect(true)->toNot->equal(false);
      });
    
      it('passes for false', function() {
        expect(false)->to->equal(false);
      });
    
      it('passes for negated false', function() {
        expect(false)->toNot->equal(true);
      });
    
      it('fails when true is expected to be false', function() {
        expect(function() {
          expect(true)->to->equal(false);
        })->to->fail();
      });
    
      it('fails when false is expected to be true', function() {
        expect(function() {
          expect(false)->to->equal(true);
        })->to->fail();
      });

      it('fails when comparing a boolean against a non-boolean', function() {
        expect(true)->toNot->equal('true');
      });

      it('fails when comparing a boolean against null', function() {
        expect(false)->toNot->equal(null);
      });
    });
  });

  describe('#equalTo', function() {
    it('is an alias of equal', function() {
      expect(true)->toBe->equalTo(true);
    });
  });

  describe('#eq', function() {
    it('is an alias of equal', function() {
      expect(true)->to->eq(true);
    });
  });
});
