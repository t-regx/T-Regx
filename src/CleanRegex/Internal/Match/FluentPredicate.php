<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;

class FluentPredicate
{
    /** @var callable */
    private $predicate;
    /** @var string */
    private $methodName;

    public function __construct(callable $predicate, string $methodName)
    {
        $this->predicate = $predicate;
        $this->methodName = $methodName;
    }

    public function test($argument): bool
    {
        $result = ($this->predicate)($argument);
        if (\is_bool($result)) {
            return $result;
        }
        throw new InvalidReturnValueException($result, $this->methodName, 'bool');
    }
}
