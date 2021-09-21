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

    public function quoted(string $delimiter): string
    {
        return $this->pattern;
    }
}
