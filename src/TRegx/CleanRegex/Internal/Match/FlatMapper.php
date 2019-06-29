<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\CleanRegex\InvalidReturnValueException;
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
        $results = $this->flatMap();
        if (empty($results)) {
            return [];
        }
        return Arrays::flatten($results);
    }

    private function flatMap(): array
    {
        return array_map([$this, 'map'], $this->elements);
    }

    public function map($object)
    {
        $value = $this->invoke($object);
        if (is_array($value)) {
            return $value;
        }
        throw InvalidReturnValueException::forFlatMap($value);
    }

    private function invoke($object)
    {
        return call_user_func($this->callback, $object);
    }
}
