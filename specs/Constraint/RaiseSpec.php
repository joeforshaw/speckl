<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#raise', function() {
    it('aliases #throwA', function() {
      expect(function() { throw new Exception(); })->to->raise(Exception::class);
    });
  });

  describe('#raiseA', function() {
    it('aliases #throwA', function() {
      expect(function() { throw new Exception(); })->to->raiseA(Exception::class);
    });
  });

  describe('#raiseAn', function() {
    it('aliases #throwA', function() {
      expect(function() { throw new Exception(); })->to->raiseAn(Exception::class);
    });
  });

  describe('#raiseAnException', function() {
    it('aliases #throwException', function() {
      expect(function() { throw new Exception(); })->to->raiseAnException();
    });
  });
});
