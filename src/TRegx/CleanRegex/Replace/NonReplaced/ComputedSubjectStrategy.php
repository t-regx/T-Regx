<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Match\Details\Match;

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
        return \call_user_func($this->mapper, $subject);
    }

    public function substituteGroup(Match $match): ?string
    {
        return \call_user_func($this->mapper, $match);
    }

    // @codeCoverageIgnoreStart
    public function useExceptionMessage(NotMatchedMessage $message): void
    {
    }
    // @codeCoverageIgnoreEnd
}
