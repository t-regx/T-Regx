<?php
namespace TRegx\CleanRegex\Internal\Messages\Subject;

use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;

class FirstMatchMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get the first match, but subject was not matched';
    }
}
