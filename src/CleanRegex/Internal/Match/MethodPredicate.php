<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;

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
        $result = ($this->predicate)($detail);
        if (\is_bool($result)) {
            return $result;
        }
        throw new InvalidReturnValueException($this->methodName, 'bool', new ValueType($result));
    }
}
