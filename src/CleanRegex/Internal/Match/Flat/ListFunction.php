<?php
namespace TRegx\CleanRegex\Internal\Match\Flat;

use TRegx\CleanRegex\Internal\Match\ArrayFunction;
use TRegx\CleanRegex\Internal\NestedArray;

/**
 * @template Targ
 * @template TkeyOut
 * @template TvalOut
 */
class ListFunction implements FlatFunction
{
    /** @var ArrayFunction<Targ, TkeyOut, TvalOut> */
    private $function;

    /**
     * @param ArrayFunction<Targ, TkeyOut, TvalOut> $function
     */
    public function __construct(ArrayFunction $function)
    {
        $this->function = $function;
    }

    /**
     * @param array<Targ> $values
     * @return list<TvalOut>
     */
    public function flatMap(array $values): array
    {
        $array = new NestedArray(\array_map([$this->function, 'apply'], $values));
        return $array->valuesList();
    }

    /**
     * @param Targ $value
     * @return array<TkeyOut, TvalOut>
     */
    public function apply($value): array
    {
        return $this->function->apply($value);
    }

    /**
     * @param mixed $key
     */
    public function mapKey($key): int
    {
        return 0;
    }
}
