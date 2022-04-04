<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Message\Message;

class FirstMatchOffsetMessage implements Message
{
    public function getMessage(): string
    {
        return 'Expected to get the first match offset, but subject was not matched';
    }
}
