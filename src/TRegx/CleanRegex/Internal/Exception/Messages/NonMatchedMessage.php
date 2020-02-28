<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages;

class NonMatchedMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return "expected to replace, but didn't";
    }
}
