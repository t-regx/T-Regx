<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Internal\Message\FromNthMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\NthMatchOffsetMessage;

class MatchOffsetMessages implements RejectionMessages
{
    public function messageUnmatched(int $index): NotMatchedMessage
    {
        return new NthMatchOffsetMessage($index);
    }

    public function messageInsufficient(int $index, int $count): NotMatchedMessage
    {
        return new FromNthMatchOffsetMessage($index, $count);
    }
}
