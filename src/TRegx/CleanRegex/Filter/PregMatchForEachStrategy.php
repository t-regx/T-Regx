<?php
namespace TRegx\CleanRegex\Filter;

use TRegx\CleanRegex\ForArray\FilterArrayKeysPattern;

class PregMatchForEachStrategy implements FilterByKeysStrategy
{
    public function filter(FilterArrayKeysPattern $filter): array
    {
        return $filter->strategy_PregMatch_ArrayFilter();
    }
}
