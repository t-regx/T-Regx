<?php
namespace TRegx\CleanRegex\Exception;

class FlagNotAllowedException extends PatternException
{
    public static function forOne(string $flag)
    {
        return new self("Regular expression flag: '$flag' is not allowed");
    }

    public static function forMany(array $flags)
    {
        $s = \implode("', '", $flags);
        return new self("Regular expression flags: ['$s'] are not allowed");
    }
}
