<?php
namespace TRegx\CleanRegex\Internal\Message\Stream\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Message\Message;

class FromNthStreamMessage implements Message
{
    /** @var Index */
    private $index;

    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->index-nth stream element, but the subject backing the stream was not matched";
    }
}
