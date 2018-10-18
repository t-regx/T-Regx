<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\CleanRegex\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Match;
use function call_user_func;

class Filter
{
    /** @var callable */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function test(Match $match): bool
    {
        $result = call_user_func($this->callback, $match);
        if (is_bool($result)) {
            return $result;
        }
        throw InvalidReturnValueException::forFilter($result);
    }
}
