<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\SafeRegex\Guard\Arrays;

class FlatMapper
{
    /** @var array */
    private $elements;
    /** @var callable */
    private $callback;

    public function __construct(array $elements, callable $callback)
    {
        $this->elements = $elements;
        $this->callback = $callback;
    }

    public function get(): array
    {
        $results = \array_map([$this, 'map'], $this->elements);
        if (empty($results)) {
            return [];
        }
        return Arrays::flatten($results);
    }

    public function map($object)
    {
        $value = \call_user_func($this->callback, $object);
        if (\is_array($value)) {
            return $value;
        }
        throw InvalidReturnValueException::forFlatMap($value);
    }
}
