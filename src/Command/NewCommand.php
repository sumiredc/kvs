<?php

declare(strict_types=1);

namespace KVS\Command;

use KVS\Infra\Repository\DataRepository;
use KVS\Request\NewRequest;
use KVS\UseCase\NewUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $io = new SymfonyStyle($input, $output);
        $dataRepository = new DataRepository;

        $request = NewRequest::new($input);
        $useCase = new NewUseCase($io, $dataRepository);
        $result = $useCase($request);

        return $result->value;
    }
}
