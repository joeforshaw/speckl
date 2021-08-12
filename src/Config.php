<?php

use Speckl\Constraint;
use Speckl\Container;
use Speckl\Scope;

Container::setDefault('runner', null);
Container::setDefault('currentBlock', null);
Container::setDefault('scopeClass', Scope::class);
Container::setDefault('constraintClass', Constraint::class);
Container::setDefault('fails', []);
Container::setDefault('successCount', 0);
Container::setDefault('failureCount', 0);
Container::setDefault('pendingCount', 0);
Container::setDefault('totalCount', 0);
Container::setDefault('formatters', []);
Container::setDefault('divider', "-------------------------------------------------------");
