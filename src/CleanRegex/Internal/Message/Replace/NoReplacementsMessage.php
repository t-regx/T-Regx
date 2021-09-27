<?php
namespace TRegx\CleanRegex\Internal\Message\Replace;

use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class NoReplacementsMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return "Replacements were supposed to be performed, but subject doesn't match the pattern";
    }
}
