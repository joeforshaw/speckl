<?php

namespace Speckl;

use Exception;
use Speckl\Exceptions\Failure;
use Speckl\Exceptions\InvalidConstraintException;

class Constraint {
  public function __construct($expectation, $negated) {
    $this->expectation = $expectation;
    $this->actual = $this->expectation->actual;
    $this->negated = $negated;
  }

  public function true() { $this->check($this->actual === true, true); }
  public function beTrue() { $this->true(); }

  public function false() { $this->check($this->actual === false, false); }
  public function beFalse() { $this->false(); }

  public function null() { $this->check(is_null($this->actual), null); }
  public function beNull() { $this->null(); }

  public function string() { $this->check(is_string($this->actual), 'string'); }
  public function aString() { $this->string(); }
  public function beAString() { $this->string(); }

  // empty() name conflicts, so can only have beEmpty()
  public function beEmpty() { $this->check(empty($this->actual), 'an empty object'); }

  public function theSameSizeAs($exp) {
    $this->expectedMessage = 'size of ' . $this->sizeOf($exp);
    $this->actualMessage = 'size of ' . $this->sizeOf($this->actual);
    $this->check(
      $this->sizeOf($exp) === $this->sizeOf($this->actual),
      'length of ' . count($exp)
    );
  }
  public function beTheSameSizeAs($exp) { $this->theSameSizeAs($exp); }
  public function theSameLengthAs($exp) { $this->theSameSizeAs($exp); }
  public function beTheSameLengthAs($exp) { $this->theSameSizeAs($exp); }

  public function AnInstanceOf($class) { $this->check($this->actual instanceof $class, "an instance of $class"); }
  public function beAnInstanceOf($class) { $this->AnInstanceOf($class); }

  public function equal($exp) { $this->check($this->actual === $exp, $exp); }
  public function equalTo($exp) { $this->equal($exp); }
  public function eq($exp) { $this->equal($exp); }

  public function greaterThan($exp) { $this->check($this->actual > $exp, "greater than $exp"); }
  public function beGreaterThan($exp) { $this->greaterThan($exp); }

  public function greaterThanOrEqualTo($exp) { $this->check($this->actual >= $exp, "greater than or equal to $exp"); }
  public function beGreaterThanOrEqualTo($exp) { $this->greaterThanOrEqualTo($exp); }
  public function gte($exp) { $this->greaterThanOrEqualTo($exp); }

  public function lessThan($exp) { $this->check($this->actual < $exp, "less than $exp"); }
  public function beLessThan($exp) { $this->lessThan($exp); }

  public function lessThanOrEqualTo($exp) { $this->check($this->actual <= $exp, "less than or equal to $exp"); }
  public function belessThanOrEqualTo($exp) { $this->lessThanOrEqualTo($exp); }
  public function lte($exp) { $this->lessThanOrEqualTo($exp); }

  public function contain($exp) {
    $this->expectedMessage = var_export($this->actual, true) . " to contain " . var_export($exp, true);
    $this->actualMessage = var_export($this->actual, true) . " doesn't contain " . var_export($exp, true);
    $this->check($this->standardizedContain($this->actual, $exp), $exp);
  }

  public function haveKey($key) {
    $this->check(
      $this->actual && array_key_exists($key, $this->actual),
      "have key \"$key\""
    );
  }
  public function haveArrayKey($key) { $this->haveKey($key); }

  // throw() name conflicts, so can only have throwA()
  public function throwA($expectedThrowableClass = Exception::class) {
    $expectedMessage = "\"$expectedThrowableClass\" was thrown";
    try {
      call_user_func($this->actual);
    } catch (Exception $actualException) {
      return $this->handleThrowable($expectedThrowableClass, $expectedMessage, $actualException);
    }
    $this->check(false, $expectedMessage);
  }
  public function throwAn() { $this->throwA(Exception::class); }
  public function throwException() { $this->throwA(Exception::class); }
  public function throwAnException() { $this->throwException(); }
  public function raise($throwableClass) { $this->throwA($throwableClass); }
  public function raiseA($throwableClass) { $this->throwA($throwableClass); }
  public function raiseAn($throwableClass) { $this->throwA($throwableClass); }
  public function raiseAnException() { $this->throwException(); }

  public function fail() {
    try {
      call_user_func($this->actual);
    } catch (Failure $failure) {
      $this->actualMessage = 'expectations fail';
      return $this->check(
        is_a($failure, Failure::class),
        $this->negated ? 'no expectations fail' : 'expectations fail'
      );
    }
    $this->actualMessage = 'no expectations fail';
    $this->check(false, $this->negated ? 'no expectations fail' : 'expectations fail');
  }

  protected function check($boolean, $expected) {
    $this->expected = $expected;
    if ($this->negated) { $boolean = !$boolean; }
    if (!$boolean) {
      $failure = new Failure($this->failureMessage());
      $failure->constraint = $this;
      throw $failure;
    }
  }

  private function actualMessage() {
    return $this->actualMessage
      ? $this->actualMessage
      : var_export($this->actual, true);
  }

  private function expectedMessage() {
    if ($this->expectedMessage) {
      return $this->expectedMessage;
    }
    return is_string($this->expected)
      ? $this->expected
      : var_export($this->expected, true);
  }

  private function failureMessage() {
    return 'Expected: ' . $this->expectedMessage() . "\n" .
           'Actual: ' . $this->actualMessage();
  }

  private function handleThrowable($expectedThrowableClass, $expectedMessage, $actualThrowable) {
    $this->actualMessage = '"' . get_class($actualThrowable) . '" was thrown';
    $this->check(
      is_a($actualThrowable, $expectedThrowableClass, true),
      $expectedMessage
    );
  }

  private function sizeOf($value) {
    if (is_string($value)) { return strlen($value); }
    return count($value);
  }

  private function standardizedContain($haystack, $needle) {
    if (is_array($haystack)) {
      if (is_array($needle) && $this->containsSubset($haystack, $needle)) {
        return true;
      }
      return in_array($needle, $haystack);
    } else if (is_string($haystack)) {
      if (is_string($needle)) {
        return strpos($haystack, $needle) !== false;
      }
      throw new InvalidConstraintException("A string can't contain an array");  
    }
    throw new InvalidConstraintException('The "contain" constraint can only accept strings or arrays');
  }

  private function containsSubset($haystack, $needle) {
    if (array_intersect($haystack, $needle) == $needle) {
      return true;
    }
    foreach ($haystack as $key => $value) {
      if (!is_array($value)) { continue; }
      if ($this->containsSubset($value, $needle)) {
        return true;
      };
    }
    return false;
  }
}
