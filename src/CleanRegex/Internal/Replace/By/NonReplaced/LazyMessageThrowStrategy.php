<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Subject;

class LazyMessageThrowStrategy implements LazySubjectRs
{
    /** @var Message */
    private $message = null;

    public function substitute(Subject $subject): ?string
    {
        throw new MissingReplacementKeyException($this->message->getMessage());
    }

    public function useExceptionMessage(Message $message): void
    {
        $this->message = $message;
    }
}
