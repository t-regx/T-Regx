<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Message\Message;

class FirstMatchMessage implements Message
{
    public function getMessage(): string
    {
        return 'Expected to get the first match, but subject was not matched';
    }
}
