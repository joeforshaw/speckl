<?php

namespace Speckl;

use Exception;
use Speckl\Exceptions\Failure;
use Speckl\Exceptions\SpecklException;
use Speckl\Formatters\ExceptionFormatter;
use Speckl\Formatters\FailureFormatter;

class FailureHandler {
  // This mapping allows different exceptions to be formatted differently. It
  // can be extended by adding rows into the "formatters" array in the container.
  private $defaultFormatters = [
    Failure::class   => FailureFormatter::class,
    Exception::class => ExceptionFormatter::class
  ];

  public function handle(Block $block, $exception) {
    $fails = Container::get('fails');
    $formatter = $this->buildFormatter($exception, $block);
    array_push($fails, $formatter);
    Container::set('fails', $fails);
  }

  public function anyFails() {
    return count(Container::get('fails')) > 0;
  }

  public function outputFails() {
    echo "\033[01;31m";
    foreach (Container::get('fails') as $i => $failFormatter) {
      echo $failFormatter->formatHeader($i) . "\n";
      echo $failFormatter->format() . "\n";
    }
    echo "\033[0m";
  }

  private function buildFormatter($exception, $block) {
    foreach ($this->formatters() as $exceptionClass => $formatterClass) {
      if (!is_a($exception, $exceptionClass)) { continue; }
      return new $formatterClass($exception, $block);
    }
    throw new SpecklException('No formatter found for ' . get_class($exception));
  }

  private function formatters() {
    return array_merge(
      Container::get('formatters'),
      $this->defaultFormatters
    );
  }
}
