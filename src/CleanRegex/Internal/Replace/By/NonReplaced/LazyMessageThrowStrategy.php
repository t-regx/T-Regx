<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Subject;

class LazyMessageThrowStrategy implements LazySubjectRs
{
    /** @var NotMatchedMessage */
    private $message = null;

    public function substitute(Subject $subject): ?string
    {
        throw new MissingReplacementKeyException($this->message->getMessage());
    }

    public function useExceptionMessage(NotMatchedMessage $message): void
    {
        $this->message = $message;
    }
}
