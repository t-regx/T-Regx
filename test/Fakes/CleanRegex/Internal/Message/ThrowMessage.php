<?php
namespace Test\Fakes\CleanRegex\Internal\Message;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class ThrowMessage implements NotMatchedMessage
{
    use Fails;

    public function getMessage(): string
    {
        throw $this->fail();
    }
}
