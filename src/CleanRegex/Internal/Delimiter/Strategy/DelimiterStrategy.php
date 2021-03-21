<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

interface DelimiterStrategy
{
    public function buildPattern(string $delimiterable): string;
}
