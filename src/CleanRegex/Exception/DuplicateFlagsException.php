<?php
namespace TRegx\CleanRegex\Exception;

class DuplicateFlagsException extends \Exception implements PatternException
{
    public static function forFlag(string $flag, string $flags): self
    {
        return new self("Regular expression flag '$flag' is duplicated in '$flags'");
    }
}
