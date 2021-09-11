<?php
namespace TRegx\CleanRegex\Internal\Messages\Subject;

use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;

class FirstMatchAsIntMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get the first match as integer, but subject was not matched';
    }
}
