<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\Messages\Subject;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;

class FirstMatchMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get first match, but subject was not matched';
    }
}
