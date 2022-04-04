<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Message\Message;

class FirstMatchAsIntMessage implements Message
{
    public function getMessage(): string
    {
        return 'Expected to get the first match as integer, but subject was not matched';
    }
}
