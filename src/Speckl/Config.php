<?php

namespace Speckl;

class Config {
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
}
