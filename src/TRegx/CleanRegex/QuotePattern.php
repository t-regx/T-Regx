<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\Pattern as InternalPattern;
use TRegx\SafeRegex\preg;

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
