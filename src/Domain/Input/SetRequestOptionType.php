<?php

declare(strict_types=1);

namespace KVS\Domain\Input;

enum SetRequestOptionType
{
    case String;
    case Integer;
    case Float;
    case Boolean;
}
