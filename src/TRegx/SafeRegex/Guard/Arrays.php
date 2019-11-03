<?php
namespace TRegx\SafeRegex\Guard;

use function array_diff_key;
use function array_merge;

class Arrays
{
    public static function equal(array $array1, array $array2): bool
    {
        return !array_diff_key($array1, $array2) && !array_diff_key($array2, $array1);
    }

    public static function flatten(array $array): array
    {
        return array_merge(...$array);
    }

    public static function getDuplicates(array $array): array
    {
        return \array_values(\array_unique(\array_intersect($array, \array_diff_assoc($array, \array_unique($array)))));
    }
}
