<?php

declare(strict_types=1);

namespace KVS\Request;

use KVS\Command\CommandInterface;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

readonly final class DeleteRequest
{
    private function __construct(
        public readonly string $key,
        public readonly string $database
    ) {}

    public static function setUp(CommandInterface $command): void
    {
        $command
            ->addArgument('key', InputArgument::REQUIRED, 'キー')
            ->addOption('database', 'd', InputOption::VALUE_REQUIRED, 'データベース');
    }

    public static function new(InputInterface $input): self
    {
        $key = $input->getArgument('key');

        $db = $input->getOption('database');
        if (empty($db)) {
            throw new InvalidOptionException('Not enough options (missing: "database").');
        }

        return new self($key, $db);
    }
}
