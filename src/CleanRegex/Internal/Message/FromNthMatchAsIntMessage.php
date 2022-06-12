<?php
namespace TRegx\CleanRegex\Internal\Message;

use TRegx\CleanRegex\Internal\Index;

class FromNthMatchAsIntMessage implements Message
{
    /** @var Index */
    private $index;
    /** @var int */
    private $total;

    public function __construct(Index $index, int $total)
    {
        $this->index = $index;
        $this->total = $total;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->index-nth match as integer, but only $this->total occurrences are available";
    }
}
