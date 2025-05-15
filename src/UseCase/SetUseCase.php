<?php

declare(strict_types=1);

namespace KVS\UseCase;

use KVS\Domain\Const\Result;
use KVS\Domain\DatabaseName;
use KVS\Domain\Exception\UseCaseException;
use KVS\Domain\Key;
use KVS\Domain\Repository\DataRepositoryInterface;
use KVS\Domain\Style\OutputStyleInterface;
use KVS\Domain\Value;
use KVS\Request\SetRequest;
use Throwable;

readonly final class SetUseCase
{
    public function __construct(
        private readonly OutputStyleInterface $style,
        private readonly DataRepositoryInterface $dataRepository
    ) {}

    public function __invoke(SetRequest $request): Result
    {
        $value = Value::new($request->value);

        try {
            $key = tryOrThrow(fn() => Key::parse($request->key), UseCaseException::class);
            $dbName = tryOrThrow(fn() => DatabaseName::new($request->database), UseCaseException::class);

            if (!$this->dataRepository->exists($dbName)) {
                throw new UseCaseException('指定されたデータベースは存在しません: %s', $dbName);
            }

            $db = $this->dataRepository->load($dbName);
            $db->set($key, $value);

            $this->dataRepository->update($db);

            return Result::Success;
        } catch (UseCaseException $ex) {
            $this->style->warning($ex->getMessage());

            return Result::Failure;
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
