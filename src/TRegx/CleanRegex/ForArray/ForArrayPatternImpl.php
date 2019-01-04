<?php
namespace TRegx\CleanRegex\ForArray;

use TRegx\CleanRegex\Filter\PregGrepArrayIntersectStrategy;
use TRegx\CleanRegex\Internal\InternalPattern;

class ForArrayPatternImpl implements ForArrayPattern
{
    /** @var array */
    private $array;
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern, array $array)
    {
        $this->pattern = $pattern;
        $this->array = $array;
    }

    public function filter(): array
    {
        return (new FilterArrayPattern($this->pattern, $this->array))->filter();
    }

    public function filterAssoc(): array
    {
        return (new FilterArrayPattern($this->pattern, $this->array))->filterAssoc();
    }

    public function filterByKeys(): array
    {
        return (new FilterArrayKeysPattern($this->pattern, $this->array))->filterByKeys(new PregGrepArrayIntersectStrategy());
    }
}
