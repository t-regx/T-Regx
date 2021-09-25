<?php
namespace TRegx\CleanRegex\Internal\Message\Subject;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class NthMatchOffsetMessage implements NotMatchedMessage
{
    /** @var int */
    private $nth;

    public function __construct(int $nth)
    {
        $this->nth = $nth;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->nth-nth match offset, but subject was not matched";
    }
}
