<?php
namespace TRegx\CleanRegex\Filter;

use TRegx\CleanRegex\ForArray\FilterArrayKeysPattern;

interface FilterByKeysStrategy
{
    public function filter(FilterArrayKeysPattern $filter): array;
}
