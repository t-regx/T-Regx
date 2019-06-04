<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;

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

    public function useExceptionMessage(NotMatchedMessage $message): void
    {
    }
}
