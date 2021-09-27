<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class FirstMatchAsIntMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get the first match as integer, but subject was not matched';
    }
}
