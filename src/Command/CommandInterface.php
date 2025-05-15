<?php

declare(strict_types=1);

namespace KVS\Command;

use Closure;
use Symfony\Component\Console\Exception\InvalidArgumentException;

interface CommandInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function addOption(
        string $name,
        string|array|null $shortcut = null,
        ?int $mode = null,
        string $description = '',
        mixed $default = null,
        array|Closure $suggestedValues = []
    ): static;

    /**
     * @throws InvalidArgumentException
     */
    public function addArgument(
        string $name,
        ?int $mode = null,
        string $description = '',
        mixed $default = null,
        array|\Closure $suggestedValues = []
    ): static;
}
