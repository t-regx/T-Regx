<?php
namespace TRegx\CleanRegex\Internal\Message\Stream\SubjectNotMatched;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class FromFirstStreamMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get the first element from fluent pattern, but the subject backing the feed was not matched';
    }
}
