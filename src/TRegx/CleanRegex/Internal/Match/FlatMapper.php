<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\CleanRegex\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\SafeRegex\Guard\Arrays;

class FlatMapper
{
    /** @var array */
    private $matchObjects;
    /** @var callable */
    private $callback;

    public function __construct(array $matchObjects, callable $callback)
    {
        $this->matchObjects = $matchObjects;
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
        return array_map([$this, 'map'], $this->matchObjects);
    }

    public function map(Match $object)
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
