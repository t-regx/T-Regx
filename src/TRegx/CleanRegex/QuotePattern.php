<?php
namespace TRegx\CleanRegex;

use TRegx\SafeRegex\preg;

class QuotePattern
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function quote(): string
    {
        return preg::quote($this->pattern);
    }
}
