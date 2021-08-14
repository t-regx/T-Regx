<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

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
