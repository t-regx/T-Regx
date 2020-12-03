<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;

class OtherwiseStrategy implements SubjectRs
{
    /** @var callable */
    private $mapper;

    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function substitute(string $subject): string
    {
        $value = \call_user_func($this->mapper, $subject);
        if (\is_string($value)) {
            return $value;
        }
        throw InvalidReturnValueException::forOtherwise($value);
    }
}
