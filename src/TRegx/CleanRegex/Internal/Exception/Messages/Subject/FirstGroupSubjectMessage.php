<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Subject;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

class FirstGroupSubjectMessage implements NotMatchedMessage
{
    /** @var string|int */
    private $nameOrIndex;

    public function __construct($nameOrIndex)
    {
        $this->nameOrIndex = $nameOrIndex;
    }

    public function getMessage(): string
    {
        return "Expected to get group '$this->nameOrIndex' from the first match, but subject was not matched at all";
    }
}
