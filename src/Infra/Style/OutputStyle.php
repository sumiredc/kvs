<?php

declare(strict_types=1);

namespace KVS\Infra\Style;

use KVS\Domain\Style\OutputStyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class OutputStyle extends SymfonyStyle implements OutputStyleInterface {}
