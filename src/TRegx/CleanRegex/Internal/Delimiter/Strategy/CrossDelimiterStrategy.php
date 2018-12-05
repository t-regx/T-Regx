<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

use function call_user_func;

class CrossDelimiterStrategy implements DelimiterStrategy
{
    /** @var callable */
    private $patternProducer;

    public function __construct(callable $patternProducer)
    {
        $this->patternProducer = $patternProducer;
    }

    public function delimiter(string $p, string $delimiter): string
    {
        return $delimiter . $this->getPattern($delimiter) . $delimiter;
    }

    public function alreadyDelimitered(string $pattern, string $delimiter): string
    {
        return $this->getPattern($delimiter);
    }

    private function getPattern(string $delimiter): string
    {
        return call_user_func($this->patternProducer, $delimiter);
    }
}
