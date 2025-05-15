<?php

declare(strict_types=1);

namespace KVS\Domain;

use Stringable;

readonly final class Value implements Stringable
{
    private function __construct(
        public readonly string|int|float|bool $data
    ) {}

    public static function new(string|int|float|bool $data): self
    {
        return new self($data);
    }

    public function __toString(): string
    {
        return var_export($this->data, true);
    }
}
