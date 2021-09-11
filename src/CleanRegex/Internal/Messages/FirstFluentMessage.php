<?php
namespace TRegx\CleanRegex\Internal\Messages;

class FirstFluentMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return "Expected to get the first element from fluent pattern, but the elements feed has 0 element(s)";
    }
}
