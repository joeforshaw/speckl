<?php

namespace Speckl;

class FailHandler {
  public function handle($throwable) {
    $fails = Container::get('fails');
    array_push($fails, $throwable);
    Container::set('fails', $fails);
  }

  public function anyFails() {
    return count(Container::get('fails')) > 0;
  }

  public function outputFails() {
    echo "-------------------------------------------------------\n";
    foreach (Container::get('fails') as $fail) {
      echo $fail->getMessage() . "\n";
    }
    echo "-------------------------------------------------------\n";
  }
}
