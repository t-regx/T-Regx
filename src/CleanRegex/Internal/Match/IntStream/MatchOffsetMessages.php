<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Internal\Message\FromNthMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\NthMatchOffsetMessage;

class MatchOffsetMessages implements RejectionMessages
{
    public function messageUnmatched(int $index): Message
    {
        return new NthMatchOffsetMessage($index);
    }

    public function messageInsufficient(int $index, int $count): Message
    {
        return new FromNthMatchOffsetMessage($index, $count);
    }
}
