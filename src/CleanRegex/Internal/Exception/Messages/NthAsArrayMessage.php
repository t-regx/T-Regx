<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages;

class NthAsArrayMessage implements NotMatchedMessage
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
        return "Expected to get the $this->nth-nth match as array, but the elements feed has $this->total element(s)";
    }
}
