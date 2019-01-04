<?php
namespace TRegx\CleanRegex\ForArray;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;
use function array_values;

class FilterArrayPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var array */
    private $array;

    public function __construct(InternalPattern $pattern, array $array)
    {
        $this->pattern = $pattern;
        $this->array = $array;
    }

    public function filter(): array
    {
        return array_values($this->filterAssoc());
    }

    public function filterAssoc(): array
    {
        return preg::grep($this->pattern->pattern, $this->array);
    }
}
