<?php
namespace TRegx\CleanRegex\Internal\ForArray;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\SafeRegex\preg;

class FilteredArray
{
    /** @var Definition */
    private $definition;
    /** @var array */
    private $array;

    public function __construct(Definition $definition, array $array)
    {
        $this->definition = $definition;
        $this->array = $array;
    }

    public function filtered(): array
    {
        foreach ($this->array as $value) {
            if (!\is_string($value)) {
                throw InvalidArgument::typeGiven("Only elements of type 'string' can be filtered", new ValueType($value));
            }
        }
        return preg::grep($this->definition->pattern, $this->array);
    }
}
