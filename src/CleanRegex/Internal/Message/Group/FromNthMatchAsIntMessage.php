<?php
namespace TRegx\CleanRegex\Internal\Message\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class FromNthMatchAsIntMessage implements NotMatchedMessage
{
    /** @var GroupKey */
    private $group;
    /** @var int */
    private $nth;
    /** @var int */
    private $total;

    public function __construct(GroupKey $group, int $nth, int $total)
    {
        $this->group = $group;
        $this->nth = $nth;
        $this->total = $total;
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group as integer from the $this->nth-nth match, but only $this->total occurrences are available";
    }
}
