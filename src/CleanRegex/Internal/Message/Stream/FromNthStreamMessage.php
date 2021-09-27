<?php
namespace TRegx\CleanRegex\Internal\Message\Stream;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class FromNthStreamMessage implements NotMatchedMessage
{
    /** @var int */
    private $index;
    /** @var int */
    private $count;

    public function __construct(int $index, int $count)
    {
        $this->index = $index;
        $this->count = $count;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->index-nth element from fluent pattern, but the elements feed has $this->count element(s)";
    }
}
