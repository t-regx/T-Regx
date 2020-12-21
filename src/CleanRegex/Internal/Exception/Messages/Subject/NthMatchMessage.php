<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Subject;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

class NthMatchMessage implements NotMatchedMessage
{
    /** @var int */
    private $index;

    public function __construct(int $index)
    {
        $this->index = $index;
    }

    public function getMessage(): string
    {
        return "Expected to get the $this->index-nth match, but subject was not matched";
    }
}