<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

class CallbackStrategy implements DelimiterStrategy
{
    /** @var callable */
    private $patternProducer;

    public function __construct(callable $patternProducer)
    {
        $this->patternProducer = $patternProducer;
    }

    public function buildPattern(string $pattern, string $delimiter): string
    {
        return $delimiter . ($this->patternProducer)($delimiter) . $delimiter;
    }

    public function shouldGuessDelimiter(): bool
    {
        return true;
    }
}
