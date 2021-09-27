<?php
namespace TRegx\CleanRegex\Internal\Message;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class GroupMessage implements NotMatchedMessage
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group, but it was not matched";
    }
}
