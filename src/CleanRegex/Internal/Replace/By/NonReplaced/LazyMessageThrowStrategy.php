<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Subjectable;

class LazyMessageThrowStrategy implements LazySubjectRs
{
    /** @var string */
    private $className;
    /** @var NotMatchedMessage */
    private $message = null;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function substitute(Subjectable $subject): ?string
    {
        $className = $this->className;
        throw new $className($this->message->getMessage());
    }

    public function useExceptionMessage(NotMatchedMessage $message): void
    {
        $this->message = $message;
    }
}
