<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\GroupFormat;

class MethodGroupMessage implements NotMatchedMessage
{
    /** @var string */
    private $method;
    /** @var string */
    private $group;

    public function __construct(string $method, $nameOrIndex)
    {
        $this->method = $method;
        $this->group = GroupFormat::group($nameOrIndex);
    }

    public function getMessage(): string
    {
        return "Expected to call $this->method() for group $this->group, but the group was not matched";
    }
}
