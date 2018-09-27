<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;
use function array_filter;
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
        return array_values(array_filter($this->array, function ($subject) {
            return preg::match($this->pattern->pattern, $subject) === 1;
        }));
    }
}
