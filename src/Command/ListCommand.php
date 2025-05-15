<?php

declare(strict_types=1);

namespace KVS\Command;

use KVS\Infra\Repository\DataRepository;
use KVS\Infra\Style\OutputStyle;
use KVS\Request\ListRequest;
use KVS\UseCase\ListUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'list',
    description: 'データの一覧を取得します',
)]
final class ListCommand extends Command implements CommandInterface
{
    protected function configure(): void
    {
        ListRequest::setUp($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new OutputStyle($input, $output);
        $dataRepository = new DataRepository;

        $request = ListRequest::new($input);
        $useCase = new ListUseCase($style, $dataRepository);
        $result = $useCase($request);

        return $result->value;
    }
}
