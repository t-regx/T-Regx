<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

class PcreIdentityStrategy implements DelimiterStrategy
{
    public function buildPattern(string $pattern, string $delimiter): string
    {
        return $pattern;
    }

    public function shouldGuessDelimiter(): bool
    {
        return false;
    }
}
