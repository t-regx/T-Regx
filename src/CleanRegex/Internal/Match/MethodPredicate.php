<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Detail;
use function call_user_func;
use function is_bool;

class MethodPredicate implements Predicate
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

    public function test(Detail $detail): bool
    {
        $result = call_user_func($this->predicate, $detail);
        if (is_bool($result)) {
            return $result;
        }
        throw new InvalidReturnValueException($result, $this->methodName, 'bool');
    }
}
