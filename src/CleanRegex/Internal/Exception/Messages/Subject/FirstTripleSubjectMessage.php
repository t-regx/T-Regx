<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Subject;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class FirstTripleSubjectMessage implements NotMatchedMessage
{
    /** @var GroupKey */
    private $group1;
    /** @var GroupKey */
    private $group2;
    /** @var GroupKey */
    private $group3;

    public function __construct(GroupKey $group1, GroupKey $group2, GroupKey $group3)
    {
        $this->group1 = $group1;
        $this->group2 = $group2;
        $this->group3 = $group3;
    }

    public function getMessage(): string
    {
        return "Expected to get a triple of groups $this->group1, $this->group2 and $this->group3 from the first match, but subject was not matched at all";
    }
}
