<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class ThrowDelimiter implements DelimiterStrategy
{
    public function buildPattern(string $delimiterable, Quotable $pattern): string
    {
        throw new \Exception("Failed to assert that DelimiterStrategy wasn't used");
    }

    public function getAlternationFactory(): AlterationFactory
    {
        throw new \Exception("Failed to assert that DelimiterStrategy wasn't used");
    }
}
