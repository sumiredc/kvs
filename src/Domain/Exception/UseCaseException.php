<?php

declare(strict_types=1);

namespace KVS\Domain\Exception;

use Exception;

final class UseCaseException extends Exception
{
    public function __construct(string $format, mixed ...$values)
    {
        parent::__construct(sprintf($format, ...$values));
    }
}
