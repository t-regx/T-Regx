<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\Message;

class FromFirstMatchIntMessage implements Message
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group as integer from the first match, but subject was not matched at all";
    }
}
