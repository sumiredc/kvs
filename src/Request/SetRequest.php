<?php

declare(strict_types=1);

namespace KVS\Request;

use KVS\Command\CommandInterface;
use KVS\Domain\Input\SetRequestOptionType;
use LogicException;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

readonly final class SetRequest
{
    private function __construct(
        public readonly string $key,
        public readonly string|int|float|bool $value,
        public readonly string $database,
        public readonly SetRequestOptionType $type,
    ) {}

    public static function setUp(CommandInterface $command): void
    {
        $command
            ->addArgument('key', InputArgument::REQUIRED, 'キー')
            ->addArgument('value', InputArgument::REQUIRED, '値')
            ->addOption('database', 'd', InputOption::VALUE_REQUIRED, 'データベース')
            ->addOption('type', 't', InputOption::VALUE_REQUIRED, '型: string(default), int, float, bool', 'string');
    }

    public static function new(InputInterface $input): self
    {
        $key = $input->getArgument('key');

        $database = $input->getOption('database');
        if (empty($database)) {
            throw new InvalidOptionException('Not enough options (missing: "option").');
        }

        $type = match ($input->getOption('type')) {
            'string', 'str', 's' => SetRequestOptionType::String,
            'integer', 'int', 'i' => SetRequestOptionType::Integer,
            'float', 'f' => SetRequestOptionType::Float,
            'boolean', 'bool', 'b' => SetRequestOptionType::Boolean,
            default => throw new InvalidOptionException('Not enough options (missing: "type").'),
        };

        $v = $input->getArgument('value');
        $value = match ($type) {
            SetRequestOptionType::String => strval($v),
            SetRequestOptionType::Integer => intval($v),
            SetRequestOptionType::Float => floatval($v),
            SetRequestOptionType::Boolean => match ($v) {
                'true' => true,
                'false' => false,
                default => boolval($v),
            },
            default => throw new LogicException('Unexpected default case reached in match') // @codeCoverageIgnore
        };

        return new self($key, $value, $database, $type);
    }
}
