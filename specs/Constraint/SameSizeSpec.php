<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#theSameSizeAs', function() {
    it('passes for strings of the same size', function() {
      expect('abc')->toBe->theSameSizeAs('123');
    });

    it('passes for arrays of the same size', function() {
      expect(['a','b','c'])->toBe->theSameSizeAs(['1','2','3']);
    });

    it('fails for strings of different sizes', function() {
      expect('abc')->toNotBe->theSameSizeAs('1234');
    });

    it('fails for arrays of different sizes', function() {
      expect(['a','b','c'])->toNotBe->theSameSizeAs(['1','2','3','4']);
    });
  });

  describe('#beTheSameSizeAs', function() {
    it('aliases #theSameSizeAs', function() {
      expect(['a','b','c'])->to->beTheSameSizeAs(['1','2','3']);
    });
  });

  describe('#theSameLengthAs', function() {
    it('aliases #theSameSizeAs', function() {
      expect(['a','b','c'])->toBe->theSameLengthAs(['1','2','3']);
    });
  });

  describe('#beTheSameLengthAs', function() {
    it('aliases #theSameSizeAs', function() {
      expect(['a','b','c'])->to->beTheSameLengthAs(['1','2','3']);
    });
  });
});
