<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Subject;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NotMatchedMessage;

class FirstMatchMessage implements NotMatchedMessage
{
    public const MESSAGE = 'Expected to get first match, but subject was not matched';

    public function getMessage(): string
    {
        return self::MESSAGE;
    }
}
