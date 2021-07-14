<?php
namespace TRegx\CleanRegex\ForArray;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\ForArray\FilteredArray;
use TRegx\SafeRegex\preg;

class ForArrayPattern
{
    /** @var FilteredArray */
    private $filteredArray;
    /** @var Definition */
    private $definition;
    /** @var array */
    private $array;

    public function __construct(Definition $definition, array $array)
    {
        $this->filteredArray = new FilteredArray($definition, $array);
        $this->definition = $definition;
        $this->array = $array;
    }

    public function filter(): array
    {
        return \array_values($this->filteredArray->filtered());
    }

    public function filterAssoc(): array
    {
        return $this->filteredArray->filtered();
    }

    public function filterByKeys(): array
    {
        return preg::grep_keys($this->definition->pattern, $this->array);
    }
}
