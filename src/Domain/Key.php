<?php

declare(strict_types=1);

namespace KVS\Domain;

use InvalidArgumentException;
use Stringable;

final class Key implements Stringable
{
    private function __construct(
        public readonly string $value,
        public private(set) ?self $next = null,
    ) {
        if (preg_match('/^[a-z0-9_]+$/', $value) === 0) {
            throw new InvalidArgumentException(sprintf('キーに使用できない文字が含まれています: %s', $value));
        }

        if ($value === '_') {
            throw new InvalidArgumentException('キーにアンダーバーのみは許可されていません');
        }

        if (strpos($value, '__') !== false) {
            throw new InvalidArgumentException(sprintf('キーにアンダーバーの連続は許可されていません: %s', $value));
        }
    }

    /** 
     * @throws InvalidArgumentException 
     */
    public static function parse(string $dotPath): self
    {
        $keys = explode('.', $dotPath);
        $head = null;
        $prev = null;

        foreach ($keys as $key) {
            $current = new self($key);
            if (!is_null($prev)) {
                $prev->next = $current;
            }

            if (is_null($head)) {
                $head = $current;
            }

            $prev = $current;
        }

        if (is_null($head)) {
            throw new InvalidArgumentException('キーの生成に失敗しました');
        }

        return $head;
    }

    public function isTail(): bool
    {
        return is_null($this->next);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
