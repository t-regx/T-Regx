<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class FirstMatchOffsetMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get the first match offset, but subject was not matched';
    }
}
