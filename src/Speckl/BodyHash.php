<?php

namespace Speckl;

use Closure;
use ReflectionFunction;
use SplFileObject;
use SplObjectStorage;

class BodyHash
{
  protected static $hashes = null;

  public static function from(Closure $closure) {
    if (!self::$hashes) {
      self::$hashes = new SplObjectStorage();
    }

    if (!isset(self::$hashes[$closure])) {
      $ref  = new ReflectionFunction($closure);
      $file = new SplFileObject($ref->getFileName());
      $file->seek($ref->getStartLine() - 1);
      $content = '';
      while ($file->key() < $ref->getEndLine()) {
        $content .= $file->current();
        $file->next();
      }
      self::$hashes[$closure] = md5(json_encode([
        $content,
        $ref->getStaticVariables()
      ]));
    }
    return self::$hashes[$closure];
  }
}
