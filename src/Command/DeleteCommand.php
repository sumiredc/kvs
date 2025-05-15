<?php

declare(strict_types=1);

namespace KVS\Command;

use KVS\Infra\Repository\DataRepository;
use KVS\Infra\Style\OutputStyle;
use KVS\Request\DeleteRequest;
use KVS\UseCase\DeleteUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'delete',
    description: '指定したキーの値を削除します',
)]
final class DeleteCommand extends Command implements CommandInterface
{
    protected function configure(): void
    {
        DeleteRequest::setUp($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new OutputStyle($input, $output);
        $dataRepository = new DataRepository;

        $request = DeleteRequest::new($input);
        $useCase = new DeleteUseCase($style, $dataRepository);
        $result = $useCase($request);

        return $result->value;
    }
}
