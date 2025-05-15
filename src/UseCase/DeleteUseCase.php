<?php

declare(strict_types=1);

namespace KVS\UseCase;

use KVS\Domain\Const\Result;
use KVS\Domain\DatabaseName;
use KVS\Domain\Exception\UseCaseException;
use KVS\Domain\Key;
use KVS\Domain\Repository\DataRepositoryInterface;
use KVS\Domain\Style\OutputStyleInterface;
use KVS\Request\DeleteRequest;
use Throwable;

readonly final class DeleteUseCase
{
    public function __construct(
        private readonly OutputStyleInterface $style,
        private readonly DataRepositoryInterface $dataRepository
    ) {}

    public function __invoke(DeleteRequest $request): Result
    {
        try {
            $dbName = tryOrThrow(fn() => DatabaseName::new($request->database), UseCaseException::class);
            $key = tryOrThrow(fn() => Key::parse($request->key), UseCaseException::class);

            if (!$this->dataRepository->exists($dbName)) {
                throw new UseCaseException('指定されたデータベースは存在しません: %s', $dbName);
            }

            $db = $this->dataRepository->load($dbName);
            if (!$db->unset($key)) {
                throw new UseCaseException('指定されたキーが見つかりませんでした: %s', $request->key);
            }

            $this->dataRepository->update($db);
            $this->style->success(sprintf('指定されたキーを削除しました: %s', $request->key));

            return Result::Success;
        } catch (UseCaseException $ex) {
            $this->style->warning($ex->getMessage());

            return Result::Failure;
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
