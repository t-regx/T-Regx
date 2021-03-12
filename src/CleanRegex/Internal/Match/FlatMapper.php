<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;

class FlatMapper
{
    /** @var FlatMapStrategy */
    private $strategy;
    /** @var callable */
    private $callback;
    /** @var string */
    private $methodName;

    public function __construct(FlatMapStrategy $strategy, callable $callback, string $methodName)
    {
        $this->strategy = $strategy;
        $this->callback = $callback;
        $this->methodName = $methodName;
    }

    public function get(array $elements): array
    {
        $results = \array_map([$this, 'map'], $elements);
        if (empty($results)) {
            return [];
        }
        return $this->strategy->flatten($results);
    }

    public function map($object): array
    {
        $value = ($this->callback)($object);
        if (\is_array($value)) {
            return $value;
        }
        throw InvalidReturnValueException::forArrayReturning($this->methodName, $value);
    }
}
