<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Nested;
use TRegx\CleanRegex\Internal\ValueType;

class FlatFunction
{
    /** @var callable */
    private $function;
    /** @var string */
    private $methodName;

    public function __construct(callable $function, string $methodName)
    {
        $this->function = $function;
        $this->methodName = $methodName;
    }

    public function map(array $arguments): Nested
    {
        return new Nested(\array_map([$this, 'apply'], $arguments));
    }

    public function apply($argument): array
    {
        $result = ($this->function)($argument);
        if (\is_array($result)) {
            return $result;
        }
        throw (new InvalidReturnValueException($this->methodName, 'array', (new ValueType($result))));
    }
}
