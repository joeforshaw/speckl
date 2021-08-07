# Speckl

Speckl is a PHP testing framework that provides the same elegant structure as Mocha, Jest and RSpec (to name a few). It provides a BDD-oriented `describe`/`it` interface that allows you to eloquently articulate what you're looking to test. 

## Example

```php
class Dog {
  public $isHappy;

  public function isGood() {
    return true;
  }

  public function tailIsWagging() {
    return $this->isHappy;
  }
}

describe(Dog::class, function() {
  beforeEach(function() {
    $this->dog = new Dog();
  });

  afterEach(function() {
    // Do stuff after
  });

  context('when the dog is happy', function() {
    it("wags it's tail", function() {
      $this->dog->isHappy = true;
      expect($this->dog->tailIsWagging())->to->equal(true);
    });
  });

  it('is a good doggo', function() {
    expect($this->dog->isGood())->to->equal(true);
  });

  it('is a bad doggo', function() {
    expect($this->dog->isGood())->to->equal(false);
  });
});
```

## TODO

* Test constraints
* Post-run report
* Run by line number
* Laravel integration
* Skip tests (xit)
