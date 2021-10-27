<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;
use TRegx\CleanRegex\Internal\ClassName;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedOptionalWorker implements OptionalWorker
{
    /** @var NotMatched */
    private $notMatched;
    /** @var string */
    private $className;
    /** @var NotMatchedMessage */
    private $message;
    /** @var Subject */
    private $subject;

    public function __construct(NotMatchedMessage $message, Subject $subject, NotMatched $notMatched, string $fallbackClassname)
    {
        $this->notMatched = $notMatched;
        $this->className = $fallbackClassname;
        $this->message = $message;
        $this->subject = $subject;
    }

    public function arguments(): array
    {
        return [$this->notMatched];
    }

    public function throwable(?string $exceptionClassname): Throwable
    {
        $className = new ClassName($exceptionClassname ?? $this->className);
        return $className->throwable($this->message, $this->subject);
    }
}
