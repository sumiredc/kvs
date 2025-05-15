<?php

declare(strict_types=1);

namespace KVS\Domain;

readonly final class Value
{
    private function __construct(
        public readonly string|int|float|bool $data
    ) {}

    public static function new(string|int|float|bool $data): self
    {
        return new self($data);
    }
}
