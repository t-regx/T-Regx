<?php
namespace TRegx\CleanRegex\Internal\Messages\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;

class MethodGetGroupMessage implements NotMatchedMessage
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group, but the group was not matched";
    }
}
