<?php

namespace Speckl;

class FailureHandler {
  public function handle(Block $block, $throwable) {
    $fails = Container::get('fails');
    array_push($fails, new FailureFormatter($block, $throwable));
    Container::set('fails', $fails);
  }

  public function anyFails() {
    return count(Container::get('fails')) > 0;
  }

  public function outputFails() {
    echo "\n\033[01;31m";
    foreach (Container::get('fails') as $i => $fail) {
      echo $fail->output($i) . "\n";
    }
    echo "\033[0m";
  }
}
