<?php
namespace Test;

use Test\Utils\ClassWithToString;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Internal\Definition;

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
            'class'      => [Definitions::pcre('//'), Definition::class],
            'function'   => [function () {
            }, 'Closure']
        ];
    }

    /**
     * @return resource
     */
    private static function getResource()
    {
        $resources = get_resources();
        return reset($resources);
    }
}
