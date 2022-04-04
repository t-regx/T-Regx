<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use Throwable;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Subject;

class SubjectStreamRejectedException extends StreamRejectedException
{
    /** @var Subject */
    private $subject;

    public function __construct(Message $message, Subject $subject)
    {
        parent::__construct($message);
        $this->subject = $subject;
    }

    public function throwable(): Throwable
    {
        return new SubjectNotMatchedException($this->exceptionMessage, $this->subject);
    }
}
