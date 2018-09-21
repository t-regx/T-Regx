<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern;

class IsPattern
{
    /** @var Pattern */
    private $pattern;

    public function __construct(Pattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function valid(): bool
    {
        return (new ValidPattern($this->pattern->originalPattern))->isValid();
    }

    public function usable(): bool
    {
        return (new ValidPattern($this->pattern->pattern))->isValid();
    }
}
