<?php
namespace TRegx\CleanRegex\Internal\Message\Stream\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class FromNthStreamMessage implements NotMatchedMessage
{
    /** @var int */
    private $nth;

    public function __construct(int $nth)
    {
        $this->nth = $nth;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->nth-nth stream element, but the subject backing the stream was not matched";
    }
}
