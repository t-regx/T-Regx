<?php
namespace TRegx\CleanRegex\Internal\Message\Replace;

use TRegx\CleanRegex\Internal\Message\Message;

class NoReplacementsMessage implements Message
{
    public function getMessage(): string
    {
        return "Replacements were supposed to be performed, but subject doesn't match the pattern";
    }
}
