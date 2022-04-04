<?php
namespace Test\Fakes\CleanRegex\Internal\Message;

use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Message\Message;

class ThrowMessage implements Message
{
    use Fails;

    public function getMessage(): string
    {
        throw $this->fail();
    }
}
