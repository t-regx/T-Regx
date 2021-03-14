<?php
namespace Test\Utils;

use TRegx\CleanRegex\Internal\InternalPattern;

class Internal
{
    public static function pattern(string $pattern, string $flags = null): InternalPattern
    {
        return InternalPattern::standard($pattern, $flags ?? '');
    }
}
