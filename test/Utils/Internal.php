<?php
namespace Test\Utils;

use AssertionError;
use TRegx\CleanRegex\Internal\Delimiter\AutomaticDelimiter;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\TrailingBackslash;

class Internal
{
    public static function pattern(string $pattern, string $flags = null): InternalPattern
    {
        if (TrailingBackslash::hasTrailingSlash($pattern)) {
            throw new AssertionError();
        }
        return new InternalPattern(AutomaticDelimiter::standard($pattern, $flags ?? ''), $pattern);
    }

    public static function pcre(string $pattern): InternalPattern
    {
        return new InternalPattern($pattern, $pattern);
    }
}
