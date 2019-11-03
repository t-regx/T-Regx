<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

class IdentityStrategy implements DelimiterStrategy
{
    public function buildPattern(string $pattern, string $delimiter): string
    {
        return $delimiter . $pattern . $delimiter;
    }

    public function shouldGuessDelimiter(): bool
    {
        return true;
    }
}
