<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

class AutomaticDelimiter
{
    public static function standard(string $pattern, string $flags): string
    {
        $delimiter = (new DelimiterFinder())->chooseDelimiter($pattern);
        return $delimiter . $pattern . $delimiter . $flags;
    }
}
