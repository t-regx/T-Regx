<?php
namespace TRegx\CleanRegex\Internal;

class TrailingBackslash
{
    public static function hasTrailingSlash(string $pattern): bool
    {
        return \substr(\str_replace('\\\\', '', $pattern), -1) === '\\';
    }
}
