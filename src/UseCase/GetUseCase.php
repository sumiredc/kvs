<?php

declare(strict_types=1);

namespace KVS\UseCase;

use KVS\Domain\Const\Result;
use KVS\Domain\DatabaseName;
use KVS\Domain\Exception\UseCaseException;
use KVS\Domain\Key;
use KVS\Domain\Repository\DataRepositoryInterface;
use KVS\Domain\Style\OutputStyleInterface;
use KVS\Request\GetRequest;
use Throwable;

readonly final class GetUseCase
{
    public function __construct(
        private readonly OutputStyleInterface $style,
        private readonly DataRepositoryInterface $dataRepository
    ) {}

    public function __invoke(GetRequest $request): Result
    {
        try {
            $dbName = tryOrThrow(fn() => DatabaseName::new($request->database), UseCaseException::class);
            $key = tryOrThrow(fn() => Key::parse($request->key), UseCaseException::class);


            if (!$this->dataRepository->exists($dbName)) {
                throw new UseCaseException('指定されたデータベースは存在しません: %s', $dbName);
            }

            $db = $this->dataRepository->load($dbName);
            $value = $db->get($key);

            if (is_null($value)) {
                throw new UseCaseException('指定された値は存在しません: %s', $request->key);
            }

            $output = is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value;
            $this->style->writeln($output);

            return Result::Success;
        } catch (UseCaseException $ex) {
            $this->style->warning($ex->getMessage());

            return Result::Failure;
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
