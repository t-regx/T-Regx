<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\Messages\Group;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;
use function sprintf;

class GroupMessage implements NotMatchedMessage
{
    private const MESSAGE = "Expected to get group '%s', but it was not matched";

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
