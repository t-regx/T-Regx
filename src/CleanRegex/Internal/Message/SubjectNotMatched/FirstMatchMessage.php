<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class FirstMatchMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get the first match, but subject was not matched';
    }
}
