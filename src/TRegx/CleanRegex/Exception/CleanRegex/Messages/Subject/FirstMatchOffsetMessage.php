<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\Messages\Subject;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;

class FirstMatchOffsetMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get the first match offset, but subject was not matched';
    }
}
