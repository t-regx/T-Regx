<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\Messages\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;

class NonMatchedMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return "expected to replace, but didn't";
    }
}
