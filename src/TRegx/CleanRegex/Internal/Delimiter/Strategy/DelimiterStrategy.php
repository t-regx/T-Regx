<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

interface DelimiterStrategy
{
    public function buildPattern(string $pattern, string $delimiter): string;

    public function shouldGuessDelimiter(): bool;
}
