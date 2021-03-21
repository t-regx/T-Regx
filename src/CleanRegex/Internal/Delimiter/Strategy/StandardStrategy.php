<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterFinder;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class StandardStrategy implements DelimiterStrategy
{
    /** @var DelimiterFinder */
    private $finder;

    public function __construct()
    {
        $this->finder = new DelimiterFinder();
    }

    public function buildPattern(string $delimiterable, Quotable $pattern): string
    {
        $delimiter = $this->finder->chooseDelimiter($delimiterable);
        return $delimiter . $pattern->quote($delimiter) . $delimiter;
    }
}
