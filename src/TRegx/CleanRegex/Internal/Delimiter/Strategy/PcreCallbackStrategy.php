<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

use function call_user_func;

class PcreCallbackStrategy implements DelimiterStrategy
{
    /** @var callable */
    private $patternProducer;

    public function __construct(callable $patternProducer)
    {
        $this->patternProducer = $patternProducer;
    }

    public function buildPattern(string $pattern, string $delimiter): string
    {
        return call_user_func($this->patternProducer, $delimiter);
    }

    public function shouldGuessDelimiter(): bool
    {
        return false;
    }
}
