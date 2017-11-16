<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern;

class FilterArrayPattern
{
    /** @var Pattern */
    private $pattern;
    /** @var array */
    private $array;

    public function __construct(Pattern $pattern, array $array)
    {
        $this->pattern = $pattern;
        $this->array = $array;
    }

    public function filter(): array
    {
        return array_filter($this->array, function ($element) {
            return (new MatchesPattern($this->pattern, $element))->matches();
        });
    }
}
