<?php

declare(strict_types=1);

namespace KVS\Infra\Repository;

use KVS\Domain\Database;
use KVS\Domain\DatabaseName;
use KVS\Domain\DatabasePath;
use KVS\Domain\Repository\DataRepositoryInterface;
use LogicException;
use RuntimeException;

readonly final class DataRepository implements DataRepositoryInterface
{
    public function load(DatabaseName $dbName): Database
    {
        $dbPath = DatabasePath::new($dbName);
        $contents = file_get_contents($dbPath->full);
        if ($contents === false) {
            throw new RuntimeException(sprintf('データベースの読み込みに失敗しました: %s', $dbPath->full));
        }

        $data = json_decode($contents);
        if (!is_array($data)) {
            throw new RuntimeException(sprintf('データの読み込みに失敗しました: %s', $dbPath->full));
        }

        return Database::parse($dbName, $data);
    }

    public function exists(DatabaseName $dbName): bool
    {
        $dbPath = DatabasePath::new($dbName);

        return $this->existsByPath($dbPath);
    }

    private function existsByPath(DatabasePath $dbPath): bool
    {
        return is_file($dbPath->full);
    }

    public function create(DatabaseName $dbName): void
    {
        $db = Database::new($dbName);
        $dbPath = DatabasePath::new($dbName);

        if ($this->existsByPath($dbPath)) {
            throw new LogicException(sprintf('データベースが既に存在しています: %s', $dbPath->full));
        }

        if (!file_put_contents($dbPath->full, $db->toJson())) {
            throw new RuntimeException(sprintf('データベースの作成に失敗しました: %s', $dbPath->full));
        }
    }

    public function update(Database $db): void
    {
        $dbPath = DatabasePath::new($db->name);

        if (!$this->existsByPath($dbPath)) {
            throw new LogicException(sprintf('データベースが存在しません: %s', $dbPath->full));
        }

        if (file_put_contents($dbPath->full, $db->toJson())) {
            throw new RuntimeException(sprintf('データベースの更新に失敗しました: %s', $dbPath->full));
        }
    }

    public function delete(DatabaseName $dbName): void
    {
        $dbPath = DatabasePath::new($dbName);

        if (!$this->existsByPath($dbPath)) {
            throw new LogicException(sprintf('データベースが存在しません: %s', $dbPath->full));
        }

        if (!unlink($dbPath->full)) {
            throw new RuntimeException(sprintf('データベースの削除に失敗しました: %s', $dbPath->full));
        }
    }
}
