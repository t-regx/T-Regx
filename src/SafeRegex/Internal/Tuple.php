<?php
namespace TRegx\SafeRegex\Internal;

use TypeError;

class Tuple
{
    public static function first(array $tuple)
    {
        if (\array_keys($tuple) === [0, 1]) {
            return $tuple[0];
        }
        throw new TypeError();
    }

    public static function second(array $tuple)
    {
        if (\array_keys($tuple) === [0, 1]) {
            return $tuple[1];
        }
        throw new TypeError();
    }
}
