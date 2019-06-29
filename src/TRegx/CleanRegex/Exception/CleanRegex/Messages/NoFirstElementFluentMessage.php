<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\Messages;

class NoFirstElementFluentMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return "Expected to get the first element from fluent pattern, but the elements feed is empty.";
    }
}
