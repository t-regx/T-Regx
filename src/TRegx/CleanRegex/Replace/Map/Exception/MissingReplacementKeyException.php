<?php
namespace TRegx\CleanRegex\Replace\Map\Exception;

use TRegx\CleanRegex\Exception\CleanRegex\CleanRegexException;

class MissingReplacementKeyException extends CleanRegexException
{
    public static function create(string $value)
    {
        return new self("Expected to replace value '$value', but such key is not found in replacement map.");
    }

    public static function forGroup(string $value, $nameOrIndex)
    {
        return new self("Expected to replace value '$value' of group '$nameOrIndex', but such key is not found in replacement map.");
    }
}
