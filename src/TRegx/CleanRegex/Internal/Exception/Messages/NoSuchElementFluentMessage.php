<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages;

class NoSuchElementFluentMessage implements NotMatchedMessage
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
        return "Expected to get the #$this->index element from fluent pattern, but the elements feed has $this->count elements.";
    }
}
