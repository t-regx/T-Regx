<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;

class GroupDetails
{
    /** @var null|string */
    public $name;
    /** @var int */
    public $index;
    /** @var GroupKey */
    public $groupId;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(?string $name, int $index, GroupKey $groupId, MatchAllFactory $allFactory)
    {
        $this->name = $name;
        $this->index = $index;
        $this->groupId = $groupId;
        $this->allFactory = $allFactory;
    }

    public function all(): array
    {
        return \array_values($this->allFactory->getRawMatches()->getGroupTexts($this->index));
    }
}
