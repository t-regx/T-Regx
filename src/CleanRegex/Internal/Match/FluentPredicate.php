<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use function call_user_func;
use function is_bool;

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
        $result = call_user_func($this->predicate, $argument);
        if (is_bool($result)) {
            return $result;
        }
        throw new InvalidReturnValueException($result, $this->methodName, 'bool');
    }
}
