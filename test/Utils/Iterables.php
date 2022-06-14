<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;

class Iterables
{
    public static function zip(array $iterable1, array $iterable2): \Generator
    {
        self::assertKeysCompatible($iterable1, $iterable2);
        return self::multipleIterator($iterable1, $iterable2);
    }

    private static function multipleIterator(array $iterable1, array $iterable2): \Generator
    {
        foreach ($iterable1 as $key => $value) {
            yield $key => [$value, $iterable2[$key]];
        }
    }

    private static function assertKeysCompatible(array $iterable1, array $iterable2): void
    {
        self::assertCompatible(\array_keys($iterable1), \array_keys($iterable2));
    }

    private static function assertCompatible(array $keys1, array $keys2): void
    {
        Assert::assertSame($keys1, $keys2, self::message($keys1, $keys2));
    }

    private static function message(array $keys1, array $keys2): string
    {
        $x1 = \json_encode($keys1);
        $x2 = \json_encode($keys2);
        return "Failed to assert two structures are compatible: $x1 and $x2";
    }
}
