<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern;
use SafeRegex\preg;

class QuotePattern
{
    /** @var Pattern */
    private $pattern;

    public function __construct(Pattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function quote(): string
    {
        return preg::quote($this->pattern->originalPattern);
    }
}
