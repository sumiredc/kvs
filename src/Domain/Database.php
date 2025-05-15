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

    public function get(Key $key): mixed
    {
        $current = &$this->data;
        $currentKey = $key;

        while (true) {
            if ($currentKey->isTail()) {
                return $current[$currentKey->value];
            }

            if (!is_array($current[$currentKey->value] ?? false)) {
                return null;
            }

            $current = &$current[$currentKey->value];
            $currentKey = $currentKey->next;
        }
    }

    public function set(Key $key, Value $value): void
    {
        $current = &$this->data;
        $currentKey = $key;

        while (true) {
            if ($currentKey->isTail()) {
                $current[$currentKey->value] = $value->data;
                break;
            }

            if (!is_array($current[$currentKey->value] ?? false)) {
                $current[$currentKey->value] = [];
            }

            $current = &$current[$currentKey->value];
            $currentKey = $currentKey->next;
        }
    }

    public function unset(Key $key): bool
    {
        $current = &$this->data;
        $currentKey = $key;

        while (true) {
            if ($currentKey->isTail()) {
                if (key_exists($currentKey->value, $current)) {
                    unset($current[$currentKey->value]);
                    return true;
                }

                // empty 
                return false;
            }

            if (!is_array($current[$currentKey->value] ?? false)) {
                return false;
            }

            $current = &$current[$currentKey->value];
            $currentKey = $currentKey->next;
        }
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
