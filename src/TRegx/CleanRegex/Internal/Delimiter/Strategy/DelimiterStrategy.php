<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

interface DelimiterStrategy
{
    public function delimiter(string $pattern, string $delimiter): string;

    public function alreadyDelimitered(string $pattern, string $delimiter): string;
}
