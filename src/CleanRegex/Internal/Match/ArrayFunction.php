<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Type\ValueType;

class ArrayFunction
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

    public function apply($argument): array
    {
        $result = ($this->function)($argument);
        if (\is_array($result)) {
            return $result;
        }
        throw new InvalidReturnValueException($this->methodName, 'array', new ValueType($result));
    }
}
