<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;

class TrailingBackslash
{
    public static function throwIfHas(string $pattern): void
    {
        if (self::hasTrailingSlash($pattern)) {
            throw new TrailingBackslashException();
        }
    }

    private static function hasTrailingSlash(string $pattern): bool
    {
        $unquoted = \str_replace('\\\\', '', $pattern);
        return \substr($unquoted, -1) === '\\';
    }
}
