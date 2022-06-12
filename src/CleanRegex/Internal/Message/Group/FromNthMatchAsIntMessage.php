<?php
namespace TRegx\CleanRegex\Internal\Message\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Message\Message;

class FromNthMatchAsIntMessage implements Message
{
    /** @var GroupKey */
    private $group;
    /** @var Index */
    private $index;
    /** @var int */
    private $total;

    public function __construct(GroupKey $group, Index $index, int $total)
    {
        $this->group = $group;
        $this->index = $index;
        $this->total = $total;
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group as integer from the $this->index-nth match, but only $this->total occurrences are available";
    }
}
