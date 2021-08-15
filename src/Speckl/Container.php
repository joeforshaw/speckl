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
    if (static::get('debug')) {
      echo "[DEBUG] container set: $key, " . (is_object($value) ? $value->id() : $value) . "\n";
    }
    $GLOBALS['speckl'][$key] = $value;
  }

  public static function setDefault($key, $value) {
    if (!static::exists($key)) {
      static::set($key, $value);
    }
  }

  public static function exists($key) {
    return array_key_exists('speckl', $GLOBALS)
        && array_key_exists($key, $GLOBALS['speckl']);
  }
}
