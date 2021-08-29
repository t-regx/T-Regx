<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\ValueType;

class OtherwiseStrategy implements SubjectRs
{
    /** @var callable */
    private $mapper;

    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function substitute(Subject $subject): string
    {
        $value = ($this->mapper)($subject->getSubject());
        if (\is_string($value)) {
            return $value;
        }
        throw InvalidReturnValueException::forOtherwise(new ValueType($value));
    }
}
