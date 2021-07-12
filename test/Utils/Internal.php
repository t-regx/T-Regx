<?php
namespace Test\Utils;

use AssertionError;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\AutomaticDelimiter;
use TRegx\CleanRegex\Internal\TrailingBackslash;

class Internal
{
    public static function pattern(string $pattern, string $flags = null): Definition
    {
        if (TrailingBackslash::hasTrailingSlash($pattern)) {
            throw new AssertionError();
        }
        return new Definition(AutomaticDelimiter::standard($pattern, $flags ?? ''), $pattern);
    }

    public static function pcre(string $pattern): Definition
    {
        return new Definition($pattern, $pattern);
    }
}
