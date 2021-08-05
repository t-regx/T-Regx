<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;

class GroupDetails
{
    /** @var GroupSignature */
    public $signature;
    /** @var GroupKey */
    public $group;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(GroupSignature $signature, GroupKey $group, MatchAllFactory $allFactory)
    {
        $this->signature = $signature;
        $this->group = $group;
        $this->allFactory = $allFactory;
    }

    public function all(): array
    {
        return \array_values($this->allFactory->getRawMatches()->getGroupTexts($this->signature->index()));
    }
}
