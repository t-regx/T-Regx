<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class MethodGroupMessage implements NotMatchedMessage
{
    /** @var string */
    private $method;
    /** @var GroupKey */
    private $groupId;

    public function __construct(string $method, GroupKey $groupId)
    {
        $this->method = $method;
        $this->groupId = $groupId;
    }

    public function getMessage(): string
    {
        return "Expected to call $this->method() for group $this->groupId, but the group was not matched";
    }
}
