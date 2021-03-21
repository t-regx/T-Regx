<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterFinder;

class CallbackStrategy implements DelimiterStrategy
{
    /** @var callable */
    private $patternProducer;
    /** @var DelimiterFinder */
    private $finder;

    public function __construct(callable $patternProducer)
    {
        $this->patternProducer = $patternProducer;
        $this->finder = new DelimiterFinder();
    }

    public function buildPattern(string $delimiterable): string
    {
        $delimiter = $this->finder->chooseDelimiter($delimiterable);
        return $delimiter . ($this->patternProducer)($delimiter) . $delimiter;
    }
}
