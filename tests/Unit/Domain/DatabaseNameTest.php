<?php

declare(strict_types=1);

use KVS\Domain\DatabaseName;

describe('new', function () {
    it(
        'match to arg to value property',
        function (string $value, string $expected) {
            $actual = DatabaseName::new($value);

            expect($actual->value)->toBe($expected);
            expect(strval($actual))->toBe($expected);
        }
    )
        ->with([
            [
                'allow_database_name',
                'allow_database_name',
            ]
        ]);

    it(
        'throws an exception on invalid value',
        function (string $value, Throwable $expected) {
            expect(fn() => DatabaseName::new($value))->toThrow($expected);
        }
    )
        ->with([
            'include upper case' => [
                'Database',
                new InvalidArgumentException('データベース名に使用できない文字が含まれています: Database')
            ],
            'include only underbar' => [
                '_',
                new InvalidArgumentException('データベース名にアンダーバーのみは許可されていません')
            ],
            'include consecutive underbars' => [
                'data__base',
                new InvalidArgumentException('データベース名に連続したアンダーバーは許可されていません: data__base')
            ],
        ]);
});
