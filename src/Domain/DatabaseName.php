<?php

declare(strict_types=1);

namespace KVS\Domain;

use InvalidArgumentException;
use Throwable;

readonly final class DatabaseName
{
    private function __construct(
        public readonly string $value
    ) {}

    /** @throws InvalidArgumentException */
    public static function new(string $value): self
    {
        if (preg_match('/^[a-z0-9_]+$/', $value) === 0) {
            new InvalidArgumentException(sprintf('使用できない文字が含まれています: %s', $value));
        }

        return new self($value);
    }

    public static function tryNew(string $value): ?self
    {
        try {
            return self::new($value);
        } catch (Throwable) {
            return null;
        }
    }
}
