<?php
namespace TRegx\CleanRegex\Internal\Message\Replace;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\Message;

class WithUnmatchedGroupMessage implements Message
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function getMessage(): string
    {
        return "Expected to replace with group $this->group, but the group was not matched";
    }
}
