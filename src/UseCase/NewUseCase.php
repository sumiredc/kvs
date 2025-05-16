<?php

declare(strict_types=1);

namespace KVS\UseCase;

use KVS\Domain\Const\Result;
use KVS\Domain\DatabaseName;
use KVS\Domain\Exception\UseCaseException;
use KVS\Domain\Repository\DataRepositoryInterface;
use KVS\Domain\Style\OutputStyleInterface;
use KVS\Request\NewRequest;
use Throwable;

readonly final class NewUseCase
{
    public function __construct(
        private readonly OutputStyleInterface $style,
        private readonly DataRepositoryInterface $dataRepository
    ) {}

    public function __invoke(NewRequest $request): Result
    {
        try {
            $dbName = tryOrThrow(fn() => DatabaseName::new($request->database), UseCaseException::class);

            if ($this->dataRepository->exists($dbName)) {
                throw new UseCaseException('データベースが既に存在します: %s', $dbName);
            }

            $this->dataRepository->create($dbName);
            $this->style->success(sprintf('データベースが作成されました: %s', $dbName));

            return Result::Success;
        } catch (UseCaseException $ex) {
            $this->style->warning($ex->getMessage());

            return Result::Failure;
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
