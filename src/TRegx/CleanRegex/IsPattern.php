<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;

class IsPattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
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
