<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class NthGroupMessage implements NotMatchedMessage
{
    /** @var GroupKey */
    private $group;
    /** @var int */
    private $index;

    public function __construct(GroupKey $group, int $index)
    {
        $this->group = $group;
        $this->index = $index;
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group from the $this->index-nth match, but the group was not matched";
    }
}
