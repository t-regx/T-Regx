<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\Messages\Group;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;
use function sprintf;

class MethodGroupMessage implements NotMatchedMessage
{
    private const MESSAGE = "Expected to call %s() for group '%s', but group was not matched";

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
