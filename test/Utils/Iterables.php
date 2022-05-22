<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;

class Iterables
{
    public static function zip(array $iterable1, array $iterable2): \Generator
    {
        Assert::assertSame(\array_keys($iterable1), \array_keys($iterable2), "Failed to zip two arrays of different keyset");
        return self::multipleIterator($iterable1, $iterable2);
    }

    private static function multipleIterator(array $iterable1, array $iterable2): \Generator
    {
        foreach ($iterable1 as $key => $value) {
            yield $key => [$value, $iterable2[$key]];
        }
    }
}
