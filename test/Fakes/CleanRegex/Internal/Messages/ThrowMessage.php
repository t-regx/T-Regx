<?php
namespace Test\Fakes\CleanRegex\Internal\Messages;

use AssertionError;
use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;

class ThrowMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        throw new AssertionError("Failed to assert that message wasn't used");
    }
}
