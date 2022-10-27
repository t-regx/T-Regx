<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Type\ValueType;

class SubjectList
{
    /** @var string[] */
    public $subjects;

    public function __construct(array $subjects)
    {
        foreach ($subjects as $subject) {
            if (!\is_string($subject)) {
                throw InvalidArgument::typeGiven("Expected an array of elements of type 'string' to be filtered", new ValueType($subject));
            }
        }
        $this->subjects = $subjects;
    }
}
