<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\ClassName;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Subject;

class Rejection
{
    /** @var Subject */
    public $subject;
    /** @var string */
    private $exceptionClassName;
    /** @var Message */
    private $message;

    public function __construct(Subject $subject, string $exceptionClassName, Message $message)
    {
        $this->subject = $subject;
        $this->exceptionClassName = $exceptionClassName;
        $this->message = $message;
    }

    public function throw(?string $exceptionClassName): void
    {
        $className = new ClassName($exceptionClassName ?? $this->exceptionClassName);
        throw $className->throwable($this->message, $this->subject);
    }
}
