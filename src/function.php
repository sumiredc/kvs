<?php

declare(strict_types=1);

/**
 * NOTE: StackTrace が消失するので要注意
 * 
 * @template T
 * @param callable(): T $callback
 * @param ?class-string<Throwable>
 * @return T
 * @throws Throwable
 */
function tryOrThrow(callable $callback, ?string $replaceException = null)
{
    if (
        !is_null($replaceException)
        && !is_subclass_of($replaceException, Throwable::class)
    ) {
        throw new InvalidArgumentException(sprintf('指定した例外クラスは許可されていません: $s', $replaceException));
    }

    try {
        return $callback();
    } catch (Throwable $th) {
        throw is_null($replaceException)
            ? $th
            : new $replaceException($th->getMessage());
    }
}
