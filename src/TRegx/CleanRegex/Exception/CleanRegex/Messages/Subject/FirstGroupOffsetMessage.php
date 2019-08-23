<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\Messages\Subject;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;

class FirstGroupOffsetMessage implements NotMatchedMessage
{
    /** @var string|int */
    private $nameOrIndex;

    public function __construct($nameOrIndex)
    {
        $this->nameOrIndex = $nameOrIndex;
    }

    public function getMessage(): string
    {
        return "Expected to get group '$this->nameOrIndex' offset from the first match, but subject was not matched at all";
    }
}
