<?php
namespace TRegx\CleanRegex\Internal\Messages;

class NthOffsetMessage implements NotMatchedMessage
{
    /** @var int */
    private $nth;
    /** @var int */
    private $total;

    public function __construct(int $nth, int $total)
    {
        $this->nth = $nth;
        $this->total = $total;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->nth-nth match offset, but only $this->total occurrences were matched";
    }
}
