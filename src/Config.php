<?php

use Speckl\Container;
use Speckl\FailureHandler;
use Speckl\Scope;

Container::setDefault('runner', null);
Container::setDefault('currentBlock', null);
Container::setDefault('scopeClass', Scope::class);
Container::setDefault('failureHandlerClass', FailureHandler::class);
Container::setDefault('fails', []);
