<?php

declare(strict_types=1);

namespace KVS\Domain\Const;

enum Result: int
{
    case Success = 0;
    case Failure = 1;
    case Invalid = 2;
}
