<?php

declare(strict_types=1);

namespace KVS\Command;

use KVS\Infra\Repository\DataRepository;
use KVS\Infra\Style\OutputStyle;
use KVS\Request\GetRequest;
use KVS\UseCase\GetUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'get',
    description: '指定したキーの値を取得します',
)]
final class GetCommand extends Command implements CommandInterface
{
    protected function configure(): void
    {
        GetRequest::setUp($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new OutputStyle($input, $output);
        $dataRepository = new DataRepository;

        $request = GetRequest::new($input);
        $useCase = new GetUseCase($style, $dataRepository);
        $result = $useCase($request);

        return $result->value;
    }
}
