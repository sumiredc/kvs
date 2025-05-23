<?php

declare(strict_types=1);

use KVS\Command\DeleteCommand;
use KVS\Command\GetCommand;
use KVS\Command\ListCommand;
use KVS\Command\NewCommand;
use KVS\Command\SetCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/const.php';
require __DIR__ . '/function.php';

$app = new Application('kvs', '0.1.0');

$app->add(new NewCommand());
$app->add(new SetCommand());
$app->add(new ListCommand());
$app->add(new GetCommand());
$app->add(new DeleteCommand());

$app->run();
