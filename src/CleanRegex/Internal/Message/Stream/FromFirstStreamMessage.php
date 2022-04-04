<?php
namespace TRegx\CleanRegex\Internal\Message\Stream;

use TRegx\CleanRegex\Internal\Message\Message;

class FromFirstStreamMessage implements Message
{
    public function getMessage(): string
    {
        return "Expected to get the first stream element, but the stream has 0 element(s)";
    }
}
