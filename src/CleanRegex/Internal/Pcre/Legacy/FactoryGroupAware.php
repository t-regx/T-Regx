<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class FactoryGroupAware implements GroupAware
{
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(MatchAllFactory $allFactory)
    {
        $this->allFactory = $allFactory;
    }

    public function getGroupKeys(): array
    {
        return $this->allFactory->getRawMatches()->getGroupKeys();
    }

    public function hasGroup(GroupKey $group): bool
    {
        return $this->allFactory->getRawMatches()->hasGroup($group);
    }
}
