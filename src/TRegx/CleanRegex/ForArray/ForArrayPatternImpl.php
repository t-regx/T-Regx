<?php
namespace TRegx\CleanRegex\ForArray;

use TRegx\CleanRegex\Filter\PregGrepArrayIntersectStrategy;
use TRegx\CleanRegex\Internal\InternalPattern;

class ForArrayPatternImpl implements ForArrayPattern
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
        return (new FilterArrayKeysPattern($this->pattern, $this->array))->filterByKeys(new PregGrepArrayIntersectStrategy());
    }

    public function strict(): ForArrayPattern
    {
        return new self($this->pattern, $this->array, true);
    }
}
