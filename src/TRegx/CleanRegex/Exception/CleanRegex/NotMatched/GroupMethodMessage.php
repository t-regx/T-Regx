<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\NotMatched;

class GroupMethodMessage implements NotMatchedMessage
{
    private const MESSAGE = "Expected to call %s() for group '%s', but group was not matched at all";

    /** @var string */
    private $message;

    public function __construct(string $method, $nameOrIndex)
    {
        $this->message = sprintf(self::MESSAGE, $method, $nameOrIndex);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
