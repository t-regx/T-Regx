<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

class PatternPhrase implements Phrase
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function conjugated(string $delimiter): string
    {
        return $this->pattern;
    }

    public function unconjugated(string $delimiter): string
    {
        return $this->pattern;
    }
}
