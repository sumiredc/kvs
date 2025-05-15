<?php

declare(strict_types=1);

namespace KVS\Request;

use KVS\Command\CommandInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

readonly final class ListRequest
{
    private function __construct(
        public readonly string $database
    ) {}

    public static function setUp(CommandInterface $command): void
    {
        $command->addArgument('database', InputArgument::REQUIRED, 'データベース');
    }

    public static function new(InputInterface $input): self
    {
        $db = $input->getArgument('database');

        return new self($db);
    }
}
