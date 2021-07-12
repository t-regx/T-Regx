<?php
namespace TRegx\CleanRegex\ForArray;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\SafeRegex\preg;

class ForArrayPattern
{
    /** @var Definition */
    private $definition;
    /** @var array */
    private $array;
    /** @var bool */
    private $throwOnNonStringElements;

    public function __construct(Definition $definition, array $array, bool $strict)
    {
        $this->definition = $definition;
        $this->array = $array;
        $this->throwOnNonStringElements = $strict;
    }

    public function filter(): array
    {
        return (new FilterArrayPattern($this->definition, $this->array, $this->throwOnNonStringElements))->filter();
    }

    public function filterAssoc(): array
    {
        return (new FilterArrayPattern($this->definition, $this->array, $this->throwOnNonStringElements))->filterAssoc();
    }

    public function filterByKeys(): array
    {
        return preg::grep_keys($this->definition->pattern, $this->array);
    }

    public function strict(): ForArrayPattern
    {
        return new self($this->definition, $this->array, true);
    }
}
