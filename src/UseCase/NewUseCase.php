<?php

declare(strict_types=1);

namespace KVS\UseCase;

use KVS\Domain\Const\Result;
use KVS\Domain\DatabaseName;
use KVS\Domain\Exception\UseCaseException;
use KVS\Domain\Repository\DataRepositoryInterface;
use KVS\Request\NewRequest;
use Symfony\Component\Console\Style\StyleInterface;
use Throwable;

readonly final class NewUseCase
{
    public function __construct(
        private readonly StyleInterface $io,
        private readonly DataRepositoryInterface $dataRepository
    ) {}

    public function __invoke(NewRequest $request): Result
    {
        $dbName = DatabaseName::tryNew($request->database);

        try {
            if (is_null($dbName)) {
                throw new UseCaseException('この名称は使用できません: %s', $request->database);
            }

            if ($this->dataRepository->exists($dbName)) {
                throw new UseCaseException('データベースが既に存在します: %s', $request->database);
            }

            $this->dataRepository->create($dbName);
            $this->io->success(sprintf('データベースが作成されました: %s', $dbName->value));

            return Result::Success;
        } catch (UseCaseException $ex) {
            $this->io->warning($ex->getMessage());

            return Result::Failure;
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
