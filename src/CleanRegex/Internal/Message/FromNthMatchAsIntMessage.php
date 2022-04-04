<?php
namespace TRegx\CleanRegex\Internal\Message;

class FromNthMatchAsIntMessage implements Message
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
        return "Expected to get the $this->nth-nth match as integer, but only $this->total occurrences are available";
    }
}
