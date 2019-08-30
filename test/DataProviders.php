<?php
namespace Test;

class DataProviders
{
    public static function invalidPregPatterns(): array
    {
        return [
            ['/{2,1}/'],
            ['/)unopened.group/'],
            ['/un(closed.group/'],
            ['/*starting.quantifier/'],
            [' /\/'],
            ['/\/'],
            ['/\\/'],
            ['/(/'],
            ['/{1}/'],
        ];
    }

    public function invalidUtf8Sequences()
    {
        return [
            ['Invalid 2 Octet Sequence', "\xc3\x28"],
            ['Invalid Sequence Identifier', "\xa0\xa1"],
            ['Invalid 3 Octet Sequence (in 2nd Octet)', "\xe2\x28\xa1"],
            ['Invalid 3 Octet Sequence (in 3rd Octet)', "\xe2\x82\x28"],
            ['Invalid 4 Octet Sequence (in 2nd Octet)', "\xf0\x28\x8c\xbc"],
            ['Invalid 4 Octet Sequence (in 3rd Octet)', "\xf0\x90\x28\xbc"],
            ['Invalid 4 Octet Sequence (in 4th Octet)', "\xf0\x28\x8c\x28"],
        ];
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
}
