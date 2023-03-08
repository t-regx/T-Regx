<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Type\ValueType;

/**
 * @template Targ
 * @template TkeyOut
 * @template TvalOut
 */
class ArrayFunction
{
    /** @var callable(Targ): array<TkeyOut, TvalOut> */
    private $function;
    /** @var string */
    private $methodName;

    /**
     * @param callable(Targ): array<TkeyOut, TvalOut> $function
     */
    public function __construct(callable $function, string $methodName)
    {
        $this->function = $function;
        $this->methodName = $methodName;
    }

    /**
     * @param Targ $argument
     * @return array<TkeyOut, TvalOut>
     */
    public function apply($argument): array
    {
        $result = ($this->function)($argument);
        if (\is_array($result)) {
            return $result;
        }
        throw new InvalidReturnValueException($this->methodName, 'array', new ValueType($result));
    }
}
