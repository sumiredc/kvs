<?php

declare(strict_types=1);

namespace KVS\Command;

use KVS\Infra\Repository\DataRepository;
use KVS\Infra\Style\OutputStyle;
use KVS\Request\SetRequest;
use KVS\UseCase\SetUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'set',
    description: '指定したキーに値を保存します',
)]
final class SetCommand extends Command implements CommandInterface
{
    protected function configure(): void
    {
        SetRequest::setUp($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new OutputStyle($input, $output);
        $dataRepository = new DataRepository;

        $request = SetRequest::new($input);
        $useCase = new SetUseCase($style, $dataRepository);
        $result = $useCase($request);

        return $result->value;
    }
}
