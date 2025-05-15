<?php

declare(strict_types=1);

namespace KVS\Domain;

use InvalidArgumentException;
use Stringable;

readonly final class DatabaseName implements Stringable
{
    private function __construct(
        public readonly string $value
    ) {}

    /** @throws InvalidArgumentException */
    public static function new(string $value): self
    {
        if (preg_match('/^[a-z0-9_]+$/', $value) === 0) {
            throw new InvalidArgumentException(sprintf('データベース名に使用できない文字が含まれています: %s', $value));
        }

        if ($value === '_') {
            throw new InvalidArgumentException('データベース名にアンダーバーのみは許可されていません');
        }

        if (strpos($value, '__') !== false) {
            throw new InvalidArgumentException(sprintf('データベース名にアンダーバーの連続は許可されていません: %s', $value));
        }

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
