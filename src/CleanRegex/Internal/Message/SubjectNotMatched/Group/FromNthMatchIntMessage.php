<?php
namespace TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Message\Message;

class FromNthMatchIntMessage implements Message
{
    /** @var GroupKey */
    private $group;
    /** @var Index */
    private $index;

    public function __construct(GroupKey $group, Index $index)
    {
        $this->group = $group;
        $this->index = $index;
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group as integer from the $this->index-nth match, but the subject was not matched at all";
    }
}
