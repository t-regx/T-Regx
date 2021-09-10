<?php
namespace TRegx\CleanRegex\Internal\Prepared\Word;

class PatternWord implements Word
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function quote(string $delimiter): string
    {
        return $this->pattern;
    }
}
