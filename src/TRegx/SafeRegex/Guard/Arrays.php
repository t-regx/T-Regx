<?php
namespace TRegx\SafeRegex\Guard;

use function array_diff_key;

class Arrays
{
    public static function equal(array $array1, array $array2): bool
    {
        return !array_diff_key($array1, $array2) && !array_diff_key($array2, $array1);
    }

    public static function flatten(array $array): array
    {
        return call_user_func_array('array_merge', $array);
    }
}
