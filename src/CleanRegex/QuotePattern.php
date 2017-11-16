<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern;
use SafeRegex\ExceptionFactory;

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
        $result = @preg_quote($this->pattern->originalPattern);
        (new ExceptionFactory())->retrieveGlobalsAndThrow('preg_quote', $result);

        return $result;
    }
}
