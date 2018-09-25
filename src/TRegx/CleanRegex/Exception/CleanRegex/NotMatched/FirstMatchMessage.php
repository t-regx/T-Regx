<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\NotMatched;

class FirstMatchMessage implements NotMatchedMessage
{
    public const MESSAGE = 'Expected to get first match in the subject, but subject was not matched at all';

    public function getMessage(): string
    {
        return self::MESSAGE;
    }
}
