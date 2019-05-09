<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NotMatchedMessage;

class NonMatchedMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return "expected to replace, but didn't";
    }
}
