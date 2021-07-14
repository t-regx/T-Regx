<?php
namespace TRegx\CleanRegex\Internal\ForArray;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Type;
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
                throw $this->throwInvalidArgumentException($value);
            }
        }
        return preg::grep($this->definition->pattern, $this->array);
    }

    private function throwInvalidArgumentException($value): InvalidArgumentException
    {
        $type = Type::asString($value);
        return new InvalidArgumentException("Only elements of type 'string' can be filtered, but $type given");
    }
}
