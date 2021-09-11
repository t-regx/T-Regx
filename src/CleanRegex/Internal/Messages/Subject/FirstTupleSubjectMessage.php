<?php
namespace TRegx\CleanRegex\Internal\Messages\Subject;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;

class FirstTupleSubjectMessage implements NotMatchedMessage
{
    /** @var GroupKey */
    private $group1;
    /** @var GroupKey */
    private $group2;

    public function __construct(GroupKey $group1, GroupKey $group2)
    {
        $this->group1 = $group1;
        $this->group2 = $group2;
    }

    public function getMessage(): string
    {
        return "Expected to get a tuple of groups $this->group1 and $this->group2 from the first match, but subject was not matched at all";
    }
}
