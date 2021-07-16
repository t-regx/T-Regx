<?php
namespace TRegx\CleanRegex\Internal;

use InvalidArgumentException;

class InvalidArgument
{
    public static function typeGiven(string $message, Type $type): InvalidArgumentException
    {
        return new InvalidArgumentException("$message, but $type given");
    }
}
