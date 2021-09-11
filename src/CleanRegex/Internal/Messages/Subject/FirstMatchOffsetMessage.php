<?php
namespace TRegx\CleanRegex\Internal\Messages\Subject;

use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;

class FirstMatchOffsetMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get the first match offset, but subject was not matched';
    }
}
