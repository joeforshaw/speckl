<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#contain', function() {
    it('passes for substrings', function() {
      expect('abcd')->to->contain('abc');
    });

    it('passes for array subsets', function() {
      expect(['a','b','c','d'])->to->contain(['a','b','c']);
    });

    it('passes for arrays containing arrays', function() {
      expect([['a','b','c']])->to->contain(['a','b','c']);
    });

    it('passes for associative array subsets', function() {
      expect(['a'=>'b','c'=>'d'])->to->contain(['a'=>'b']);
    });

    it('passes for associative arrays containing arrays', function() {
      expect(['a'=>['1','2','3']])->to->contain(['1','2','3']);
    });

    it("fails for strings that aren't substrings", function() {
      expect('abcd')->toNot->contain('123');
    });

    it("fails for arrays that aren't subsets", function() {
      expect(['a','b','c','d'])->toNot->contain(['1','2','3']);
    });

    it("fails for associative arrays containing arrays when the subset can't be found", function() {
      expect(['a'=>['1','2','3']])->toNot->contain(['a','b']);
    });
  });
});
