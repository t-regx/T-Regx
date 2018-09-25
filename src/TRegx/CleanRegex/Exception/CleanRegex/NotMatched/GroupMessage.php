<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\NotMatched;

class GroupMessage implements NotMatchedMessage
{
    private const MESSAGE = "Expected to get group '%s', but group was not matched at all";

    /** @var string */
    private $message;

    public function __construct($nameOrIndex)
    {
        $this->message = sprintf(self::MESSAGE, $nameOrIndex);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
