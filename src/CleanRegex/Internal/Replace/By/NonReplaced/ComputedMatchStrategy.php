<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Detail;

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

    public function substituteGroup(Detail $detail): string
    {
        $result = \call_user_func($this->mapper, $detail);
        if ($result === null) {
            throw new InvalidReturnValueException(null, $this->callingMethod, 'string');
        }
        return $result;
    }
}
