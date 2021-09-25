<?php
namespace Test\Fakes\CleanRegex\Internal\Message;

use AssertionError;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class ThrowMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        throw new AssertionError("Failed to assert that message wasn't used");
    }
}
