<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Internal\Message\FromNthMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\NthMatchAsIntMessage;

class MatchIntMessages implements RejectionMessages
{
    public function messageUnmatched(int $index): Message
    {
        return new NthMatchAsIntMessage($index);
    }

    public function messageInsufficient(int $index, int $count): Message
    {
        return new FromNthMatchAsIntMessage($index, $count);
    }
}
