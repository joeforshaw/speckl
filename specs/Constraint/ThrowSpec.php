<?php

use Speckl\Constraint;

class TestThrowConstraintException extends Exception {}
class AnotherTestThrowConstraintException extends Exception {}

describe(Constraint::class, function() {
  describe('#throwA', function() {
    context('with an Exception class argument', function() {
      it('passes when an Exception instance is thrown', function() {
        expect(function() {
          throw new TestThrowConstraintException();
        })->to->throwA(Exception::class);
      });

      it('passes when an Exception subclass instance is thrown', function() {
        expect(function() {
          throw new Exception();
        })->to->throwA(Exception::class);
      });
    });

    context('with an Exception subclass argument', function() {
      it('passes when the subclass instance is thrown', function() {
        expect(function() {
          throw new TestThrowConstraintException();
        })->to->throwA(TestThrowConstraintException::class);
      });

      it('fails when a superclass instance is thrown', function() {
        expect(function() {
          throw new Exception();
        })->toNot->throwA(TestThrowConstraintException::class);
      });
    });

    it('fails when no exceptions are thrown', function() {
      expect(function() {})->toNot->throwA(TestThrowConstraintException::class);
    });

    it('fails when a different type of exception is thrown', function() {
      expect(function() {
        throw new AnotherTestThrowConstraintException();
      })->toNot->throwA(TestThrowConstraintException::class);
    });
  });

  describe('#throwAn', function() {
    it('aliases #throwA', function() {
      expect(function() { throw new Exception(); })->to->throwAn(Exception::class);
    });
  });

  describe('#throwException', function() {
    it('passes when an Exception is thrown', function() {
      expect(function() {
        throw new Exception();
      })->to->throwException();
    });

    it('passes when a subclass of Exception is thrown', function() {
      expect(function() {
        throw new TestThrowConstraintException();
      })->to->throwException();
    });
  });

  describe('#throwAnException', function() {
    it('aliases #throwException', function() {
      expect(function() {
        throw new Exception();
      })->to->throwAnException();
    });
  });
});
