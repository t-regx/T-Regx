<?php
namespace TRegx\CleanRegex\Internal\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Match;

class ComputedMatchStrategy implements MatchRs
{
    /** @var callable */
    private $mapper;
    /** @var string */
    private $callingMethod;

    public function __construct(callable $mapper, string $callingMethod)
    {
        $this->mapper = $mapper;
        $this->callingMethod = $callingMethod;
    }

    public function substituteGroup(Match $match): string
    {
        $result = \call_user_func($this->mapper, $match);
        if ($result === null) {
            throw new InvalidReturnValueException(null, $this->callingMethod, "string");
        }
        return $result;
    }
}
