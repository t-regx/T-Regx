<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

class ComputedSubjectStrategy implements ReplaceSubstitute
{
    /** @var callable */
    private $mapper;

    function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function substitute(string $subject): ?string
    {
        return call_user_func($this->mapper, $subject);
    }
}
