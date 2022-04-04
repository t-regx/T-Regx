<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\Group;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromNthMatchOffsetMessage;

class GroupOffsetMessages implements RejectionMessages
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function messageUnmatched(int $index): Message
    {
        return new FromNthMatchOffsetMessage($this->group, $index);
    }

    public function messageInsufficient(int $index, int $count): Message
    {
        return new Group\FromNthMatchOffsetMessage($this->group, $index, $count);
    }
}
