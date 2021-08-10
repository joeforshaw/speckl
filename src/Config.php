<?php

use Speckl\Container;
use Speckl\ExampleBlock;
use Speckl\FailHandler;
use Speckl\GroupBlock;
use Speckl\Scope;

Container::setDefault('runner', null);
Container::setDefault('currentBlock', null);
Container::setDefault('groupBlockClass', GroupBlock::class);
Container::setDefault('exampleBlockClass', ExampleBlock::class);
Container::setDefault('scopeClass', Scope::class);
Container::setDefault('failHandlerClass', FailHandler::class);
Container::setDefault('fails', []);
