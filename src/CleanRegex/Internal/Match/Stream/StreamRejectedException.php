<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use Exception;
use TRegx\CleanRegex\Internal\Match\Rejection;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Subject;

class StreamRejectedException extends Exception
{
    /** @var Subject */
    private $subject;
    /** @var string */
    private $exceptionClassName;
    /** @var Message */
    private $notMatchedMessage;

    public function __construct(Subject $subject, string $exceptionClassName, Message $notMatchedMessage)
    {
        parent::__construct();
        $this->subject = $subject;
        $this->exceptionClassName = $exceptionClassName;
        $this->notMatchedMessage = $notMatchedMessage;
    }

    public function rejection(): Rejection
    {
        return new Rejection($this->subject, $this->exceptionClassName, $this->notMatchedMessage);
    }

    public function notMatchedMessage(): Message
    {
        return $this->notMatchedMessage;
    }
}
