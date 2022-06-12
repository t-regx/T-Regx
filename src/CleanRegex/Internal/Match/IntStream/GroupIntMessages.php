<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Message\Group;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromNthMatchIntMessage;

class GroupIntMessages implements RejectionMessages
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function messageUnmatched(Index $index): Message
    {
        return new FromNthMatchIntMessage($this->group, $index);
    }

    public function messageInsufficient(Index $index, int $count): Message
    {
        return new Group\FromNthMatchAsIntMessage($this->group, $index, $count);
    }
}
