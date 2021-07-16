<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class GroupFormat
{
    public static function group($nameOrIndex): string
    {
        if (\is_string($nameOrIndex)) {
            return "'$nameOrIndex'";
        }
        if (\is_int($nameOrIndex)) {
            return "#$nameOrIndex";
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}
