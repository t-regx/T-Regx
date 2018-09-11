<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\preg;

class QuotePattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function quote(): string
    {
        return preg::quote($this->pattern->originalPattern);
    }
}
