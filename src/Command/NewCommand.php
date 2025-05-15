<?php

declare(strict_types=1);

namespace KVS\Command;

use KVS\Infra\Repository\DataRepository;
use KVS\Infra\Style\OutputStyle;
use KVS\Request\NewRequest;
use KVS\UseCase\NewUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'new',
    description: 'データベースを作成します',
)]
final class NewCommand extends Command implements CommandInterface
{
    protected function configure(): void
    {
        NewRequest::setUp($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new OutputStyle($input, $output);
        $dataRepository = new DataRepository;

        $request = NewRequest::new($input);
        $useCase = new NewUseCase($style, $dataRepository);
        $result = $useCase($request);

        return $result->value;
    }
}
