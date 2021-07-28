<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class ReplacementWithUnmatchedGroupMessage implements NotMatchedMessage
{
    /** @var GroupKey */
    private $groupId;

    public function __construct(GroupKey $groupId)
    {
        $this->groupId = $groupId;
    }

    public function getMessage(): string
    {
        return "Expected to replace with group $this->groupId, but the group was not matched";
    }
}
