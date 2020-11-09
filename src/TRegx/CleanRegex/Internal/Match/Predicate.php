<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Detail;
use function call_user_func;
use function is_bool;

class Predicate
{
    /** @var callable */
    private $predicate;

    public function __construct(callable $predicate)
    {
        $this->predicate = $predicate;
    }

    public function test(Detail $match): bool
    {
        $result = call_user_func($this->predicate, $match);
        if (is_bool($result)) {
            return $result;
        }
        throw InvalidReturnValueException::forFilter($result);
    }
}
