<?php

declare(strict_types=1);

use KVS\Command\NewCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/const.php';

$app = new Application('kvs', '0.1.0');

$app->add(new NewCommand());

$app->run();
