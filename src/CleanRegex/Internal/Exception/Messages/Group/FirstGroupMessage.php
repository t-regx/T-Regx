<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Group;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\GroupFormat;

class FirstGroupMessage implements NotMatchedMessage
{
    /** @var string */
    private $group;

    public function __construct($nameOrIndex)
    {
        $this->group = GroupFormat::group($nameOrIndex);
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group from the first match, but the group was not matched";
    }
}
