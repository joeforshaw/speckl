# Speckl

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
    // Do stuff before
  });

  afterEach(function() {
    // Do stuff after
  });

  context('when the dog is happy', function() {
    it("wags it's tail", function() {
      $dog = new Dog();
      $dog->isHappy = true;
      expect($dog->tailIsWagging())->to->equal(true);
    });
  });

  it('is a good doggo', function() {
    $dog = new Dog();
    expect($dog->isGood())->to->equal(true);
  });

  it('is a bad doggo', function() {
    $dog = new Dog();
    expect($dog->isGood())->to->equal(false);
  });
});
```

## TODO

* Tree structure
* Line numbers
* Executable binary
* Loading files