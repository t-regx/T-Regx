<?php
namespace TRegx\CleanRegex\Exception;

class DuplicateFlagsException extends PatternException
{
    public static function forFlag(string $flag, string $flags): DuplicateFlagsException
    {
        return new self("Regular expression flag: '$flag' is duplicated in '$flags'");
    }
}
