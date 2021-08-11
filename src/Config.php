<?php

use Speckl\Container;
use Speckl\ExampleBlock;
use Speckl\FailureHandler;
use Speckl\GroupBlock;
use Speckl\Scope;

Container::setDefault('runner', null);
Container::setDefault('currentBlock', null);
Container::setDefault('groupBlockClass', GroupBlock::class);
Container::setDefault('exampleBlockClass', ExampleBlock::class);
Container::setDefault('scopeClass', Scope::class);
Container::setDefault('failureHandlerClass', FailureHandler::class);
Container::setDefault('fails', []);
