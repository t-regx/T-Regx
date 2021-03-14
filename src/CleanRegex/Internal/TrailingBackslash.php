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

    public static function hasTrailingSlash(string $pattern): bool
    {
        return \substr(\str_replace('\\\\', '', $pattern), -1) === '\\';
    }
}
