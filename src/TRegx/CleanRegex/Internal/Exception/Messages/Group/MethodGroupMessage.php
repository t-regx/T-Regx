<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

class MethodGroupMessage implements NotMatchedMessage
{
    /** @var string */
    private $method;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(string $method, $nameOrIndex)
    {
        $this->method = $method;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function getMessage(): string
    {
        return "Expected to call $this->method() for group '$this->nameOrIndex', but the group was not matched";
    }
}
