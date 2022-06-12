<?php
namespace TRegx\CleanRegex\Internal\Message\Stream;

use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Message\Message;

class FromNthStreamMessage implements Message
{
    /** @var Index */
    private $index;
    /** @var int */
    private $count;

    public function __construct(Index $index, int $count)
    {
        $this->index = $index;
        $this->count = $count;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->index-nth stream element, but the stream has $this->count element(s)";
    }
}
