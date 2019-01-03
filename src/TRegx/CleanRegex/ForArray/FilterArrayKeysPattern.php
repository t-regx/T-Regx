<?php
namespace TRegx\CleanRegex\ForArray;

use TRegx\CleanRegex\Filter\FilterByKeysStrategy;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;
use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_keys;

class FilterArrayKeysPattern
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

    public function filterByKeys(FilterByKeysStrategy $strategy): array
    {
        return $strategy->filter($this);
    }

    public function strategy_PregMatch_ArrayFilter(): array
    {
        return array_filter($this->array, function ($key) {
            return preg::match($this->pattern->pattern, $key) === 1;
        }, ARRAY_FILTER_USE_KEY);
    }

    public function strategy_PregGrep_ArrayIntersect(): array
    {
        $filteredKeys = preg::grep($this->pattern->pattern, array_keys($this->array));
        return array_intersect_key($this->array, array_flip($filteredKeys));
    }
}
