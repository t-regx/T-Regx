<?php
namespace TRegx\CleanRegex\Internal;

class Arrays
{
    public static function flatten(array $array): array
    {
        return \array_merge(...$array);
    }

    public static function getDuplicates(array $array): array
    {
        return \array_values(\array_unique(\array_intersect($array, \array_diff_assoc($array, \array_unique($array)))));
    }
}
