<?php
namespace Test;

use Test\Utils\ClassWithToString;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Details\Detail;

class DataProviders
{
    public static function invalidPregPatterns(): array
    {
        return [
            ['/{2,1}/'],
            ['/)unopened.group/'],
            ['/*starting.quantifier/'],
            [' /\/'],
            ['/\\/'],
            ['/(/'],
            ['/{1}/'],
        ];
    }

    public static function invalidStandardPatterns(): array
    {
        return [
            ['{2,1}'],
            [')unopened.group'],
            ['*starting.quantifier'],
            ['\\'],
            ['('],
            ['{1}'],
        ];
    }

    public function invalidUtf8Sequences(): array
    {
        return \TRegx\DataProvider\DataProviders::each([
            'Invalid 2 Octet Sequence'                => "\xc3\x28",
            'Invalid Sequence Identifier'             => "\xa0\xa1",
            'Invalid 3 Octet Sequence (in 2nd Octet)' => "\xe2\x28\xa1",
            'Invalid 3 Octet Sequence (in 3rd Octet)' => "\xe2\x82\x28",
            'Invalid 4 Octet Sequence (in 2nd Octet)' => "\xf0\x28\x8c\xbc",
            'Invalid 4 Octet Sequence (in 3rd Octet)' => "\xf0\x90\x28\xbc",
            'Invalid 4 Octet Sequence (in 4th Octet)' => "\xf0\x28\x8c\x28",
        ]);
    }

    public static function namedAndIndexedGroups_mixed_keys(): array
    {
        return [
            'potentially invalid' => [[], []],
            'no groups'           => [[0], []],

            'no named groups' => [
                [0, 1, 2, 3, 4],
                [null, null, null, null]
            ],

            'all named groups' => [
                [0, 'one', 1, 'two', 2, 'three', 3],
                ['one', 'two', 'three']
            ],
            'named in middle'  => [
                [0, 1, 'two', 2, 3],
                [null, 'two', null]
            ],
            'named at edges'   => [
                [0, 'one', 1, 2, 'three', 3],
                ['one', null, 'three']
            ]
        ];
    }

    public static function allPhpTypes(string ...$except): array
    {
        return array_diff_key(self::typesMap(), array_flip($except));
    }

    private static function typesMap(): array
    {
        return [
            'null'       => [null, 'null'],
            'true'       => [true, 'boolean (true)'],
            'false'      => [false, 'boolean (false)'],
            'int'        => [2, 'integer (2)'],
            'float'      => [2.23, 'double (2.23)'],
            'string'     => ["She's sexy", "string ('She\'s sexy')"],
            'array'      => [[1, new \stdClass(), 3], 'array (3)'],
            'resource'   => [self::getResource(), 'resource'],
            'stdClass'   => [new \stdClass(), 'stdClass'],
            '__toString' => [new ClassWithToString('string'), 'Test\Utils\ClassWithToString'],
            'class'      => [InternalPattern::pcre('//'), InternalPattern::class],
            'function'   => [function () {
            }, 'Closure']
        ];
    }

    private static function getResource()
    {
        $resources = get_resources();
        return reset($resources);
    }

    public function groupReplaceFallbacks(): array
    {
        return [
            'orElseThrow'   => ['orElseThrow', []],
            'orElseIgnore'  => ['orElseIgnore', []],
            'orElseEmpty'   => ['orElseEmpty', []],
            'orElseWith'    => ['orElseWith', ['word']],
            'orElseCalling' => ['orElseCalling', [function (Detail $detail) {
                return "fallback: '$detail'";
            }]],
        ];
    }
}
