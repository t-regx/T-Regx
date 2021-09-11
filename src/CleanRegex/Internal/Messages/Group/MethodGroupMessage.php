<?php
namespace TRegx\CleanRegex\Internal\Messages\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;

class MethodGroupMessage implements NotMatchedMessage
{
    /** @var string */
    private $method;
    /** @var GroupKey */
    private $group;

    public function __construct(string $method, GroupKey $group)
    {
        $this->method = $method;
        $this->group = $group;
    }

    public function getMessage(): string
    {
        return "Expected to call $this->method() for group $this->group, but the group was not matched";
    }
}
