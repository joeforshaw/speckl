<?php

use Speckl\Constraint;

describe(Constraint::class, function() {
  describe('#haveKey', function() {
    it('passes when the array has the key', function() {
      expect(['key' => 'value'])->to->haveKey('key');
    });

    it('fails when the array is empty', function() {
      expect([])->toNot->haveKey('key');
    });

    it("fails when the array doesn't contain the key", function() {
      expect(['other_key' => 'other_value'])->toNot->haveKey('key');
    });

    it("fails when the array is null", function() {
      expect(null)->toNot->haveKey('key');
    });
  });

  describe('#haveArrayKey', function() {
    it('aliases #haveKey', function() {
      expect(['key' => 'value'])->to->haveKey('key');
    });
  });
});
