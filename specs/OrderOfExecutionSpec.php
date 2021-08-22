<?php

describe('Order of run execution', function() {
  $this->counter = 1;
  
  it('is the initial value', function() {
    expect($this->counter)->to->eq(1);
  });

  $this->counter++;

  it('is the initial value + 1 after scope increment', function() {
    expect($this->counter)->to->eq(2);
  });

  context('where the beforeEach increments', function() {
    beforeEach(function() {
      $this->counter++;
    });

    it('is the initial value + 2 after scope increment and beforeEach call', function() {
      expect($this->counter)->to->eq(3);
    });

    it('is the initial value + 2 after scope increment and two beforeEach calls', function() {
      expect($this->counter)->to->eq(3);
    });
  });
});
