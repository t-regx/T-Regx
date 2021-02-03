<?php
namespace TRegx\CleanRegex\ForArray;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;

class ForArrayPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var array */
    private $array;
    /** @var bool */
    private $throwOnNonStringElements;

    public function __construct(InternalPattern $pattern, array $array, bool $strict)
    {
        $this->pattern = $pattern;
        $this->array = $array;
        $this->throwOnNonStringElements = $strict;
    }

    public function filter(): array
    {
        return (new FilterArrayPattern($this->pattern, $this->array, $this->throwOnNonStringElements))->filter();
    }

    public function filterAssoc(): array
    {
        return (new FilterArrayPattern($this->pattern, $this->array, $this->throwOnNonStringElements))->filterAssoc();
    }

    public function filterByKeys(): array
    {
        return preg::grep_keys($this->pattern->pattern, $this->array);
    }

    public function strict(): ForArrayPattern
    {
        return new self($this->pattern, $this->array, true);
    }
}
