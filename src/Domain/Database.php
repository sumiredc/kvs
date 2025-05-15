<?php

declare(strict_types=1);

namespace KVS\Domain;

use RuntimeException;

final class Database
{
    private function __construct(
        public readonly DatabaseName $name,
        public private(set) array $data,
    ) {}

    public static function new(DatabaseName $dbName): self
    {
        return new self($dbName, []);
    }

    public static function parse(DatabaseName $dbName, array $data): self
    {
        return new self($dbName, $data);
    }

    public function toJson(): string
    {
        $json = json_encode($this->data);
        if ($json === false) {
            throw new RuntimeException('json へのエンコードに失敗しました');
        }

        return $json;
    }
}
