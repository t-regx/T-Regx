<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

class GroupMessage implements NotMatchedMessage
{
    private $nameOrIndex;

    public function __construct($nameOrIndex)
    {
        $this->nameOrIndex = $nameOrIndex;
    }

    public function getMessage(): string
    {
        return "Expected to get group '$this->nameOrIndex', but it was not matched";
    }
}
