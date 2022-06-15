<?php
namespace TRegx\CleanRegex\Internal\Match\Flat;

use TRegx\CleanRegex\Internal\Match\ArrayFunction;
use TRegx\CleanRegex\Internal\NestedArray;

class ListFunction implements FlatFunction
{
    /** @var ArrayFunction */
    private $function;

    public function __construct(ArrayFunction $function)
    {
        $this->function = $function;
    }

    public function flatMap(array $values): array
    {
        $array = new NestedArray(\array_map([$this->function, 'apply'], $values));
        return $array->valuesList();
    }

    public function apply($value): array
    {
        return $this->function->apply($value);
    }

    public function mapKey($key): int
    {
        return 0;
    }
}
