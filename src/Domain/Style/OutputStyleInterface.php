<?php

declare(strict_types=1);

namespace KVS\Domain\Style;

use Symfony\Component\Console\Style\StyleInterface;

interface OutputStyleInterface extends StyleInterface
{
    public function write(string|iterable $messages, bool $newline = false, int $options = 0): void;

    public function writeln(string|iterable $messages, int $options = 0): void;
}
