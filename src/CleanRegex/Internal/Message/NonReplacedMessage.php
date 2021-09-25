<?php
namespace TRegx\CleanRegex\Internal\Message;

class NonReplacedMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return "Replacements were supposed to be performed, but subject doesn't match the pattern";
    }
}
