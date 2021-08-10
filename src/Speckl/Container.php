<?php

namespace Speckl;

class Container {
  public static function get($key) {
    if (!array_key_exists('speckl', $GLOBALS)) {
      $GLOBALS['speckl'] = [];
    }
    return $GLOBALS['speckl'][$key];
  }

  public static function set($key, $value) {
    if (!array_key_exists('speckl', $GLOBALS)) {
      $GLOBALS['speckl'] = [];
    }
    $GLOBALS['speckl'][$key] = $value;
  }

  public static function setDefault($key, $value) {
    if (!static::get($key)) {
      static::set($key, $value);
    }
  }
}
