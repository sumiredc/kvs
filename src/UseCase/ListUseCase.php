<?php

declare(strict_types=1);

namespace KVS\UseCase;

use KVS\Domain\Const\Result;
use KVS\Domain\DatabaseName;
use KVS\Domain\Exception\UseCaseException;
use KVS\Domain\Repository\DataRepositoryInterface;
use KVS\Domain\Style\OutputStyleInterface;
use KVS\Request\ListRequest;
use Throwable;

readonly final class ListUseCase
{
    public function __construct(
        private readonly OutputStyleInterface $style,
        private readonly DataRepositoryInterface $dataRepository
    ) {}

    public function __invoke(ListRequest $request): Result
    {
        try {
            $dbName = tryOrThrow(fn() => DatabaseName::new($request->database), UseCaseException::class);

            if (!$this->dataRepository->exists($dbName)) {
                throw new UseCaseException('指定されたデータベースは存在しません: %s', $dbName);
            }

            $db = $this->dataRepository->load($dbName);

            $this->style->writeln(json_encode($db->data, JSON_PRETTY_PRINT));

            return Result::Success;
        } catch (UseCaseException $ex) {
            $this->style->warning($ex->getMessage());

            return Result::Failure;
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
