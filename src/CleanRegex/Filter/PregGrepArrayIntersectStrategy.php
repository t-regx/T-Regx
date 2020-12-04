<?php
namespace TRegx\CleanRegex\Filter;

use TRegx\CleanRegex\ForArray\FilterArrayKeysPattern;

class PregGrepArrayIntersectStrategy implements FilterByKeysStrategy
{
    public function filter(FilterArrayKeysPattern $filter): array
    {
        return $filter->strategy_PregGrep_ArrayIntersect();
    }
}
