<?php

declare(strict_types=1);

namespace KVS\Domain\Repository;

use KVS\Domain\Database;
use KVS\Domain\DatabaseName;

interface DataRepositoryInterface
{
    public function load(DatabaseName $dbName): Database;

    public function exists(DatabaseName $dbName): bool;

    public function create(DatabaseName $dbName): void;

    public function update(Database $db): void;

    public function delete(DatabaseName $dbName): void;
}
