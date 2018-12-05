<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

class IdentityDelimiterStrategy implements DelimiterStrategy
{
    public function delimiter(string $pattern, string $delimiter): string
    {
        return $delimiter . $pattern . $delimiter;
    }

    public function alreadyDelimitered(string $pattern, string $delimiter): string
    {
        return $pattern;
    }
}
