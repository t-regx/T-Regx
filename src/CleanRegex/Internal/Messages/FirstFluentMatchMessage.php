<?php
namespace TRegx\CleanRegex\Internal\Messages;

class FirstFluentMatchMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return "Expected to get the first element from fluent pattern, but the subject backing the feed was not matched";
    }
}
