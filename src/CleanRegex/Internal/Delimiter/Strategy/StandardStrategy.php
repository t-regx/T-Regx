<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterFinder;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class StandardStrategy implements DelimiterStrategy
{
    /** @var DelimiterFinder */
    private $finder;
    /** @var string */
    private $flags;

    public function __construct(string $flags)
    {
        $this->finder = new DelimiterFinder();
        $this->flags = $flags;
    }

    public function buildPattern(string $delimiterable, Quotable $pattern): string
    {
        $delimiter = $this->finder->chooseDelimiter($delimiterable);
        return $delimiter . $pattern->quote($delimiter) . $delimiter . $this->flags;
    }
}
