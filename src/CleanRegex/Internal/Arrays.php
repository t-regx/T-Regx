<?php
namespace TRegx\CleanRegex\Internal;

class Arrays
{
    public static function flatten(array $array): array
    {
        if (empty($array)) {
            return [];
        }
        return \array_merge(...$array);
    }
}
