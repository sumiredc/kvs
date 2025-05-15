<?php

declare(strict_types=1);

namespace KVS\Domain;

final class DatabasePath
{
    public string $relative {
        get => sprintf('%s/%s.json', DATABASE_PATH, $this->value);
    }

    public string $full {
        get => sprintf('%s/%s.json', DATABASE_FULL_PATH, $this->value);
    }

    private function __construct(
        private readonly string $value,
    ) {}

    public static function new(DatabaseName $dbName): self
    {
        return new self($dbName->value);
    }
}
