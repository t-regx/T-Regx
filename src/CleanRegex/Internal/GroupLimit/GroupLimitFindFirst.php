<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacadeMatched;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\FirstNamedGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchMessage;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\SubjectEmptyOptional;
use TRegx\CleanRegex\Match\Optional;

class GroupLimitFindFirst
{
    /** @var Base */
    private $base;
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupKey */
    private $group;

    public function __construct(Base $base, GroupAware $groupAware, GroupKey $group)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->group = $group;
    }

    public function getOptionalForGroup(callable $consumer): Optional
    {
        $first = $this->base->matchOffset();
        if ($this->matched($first)) {
            return $this->matchedOptional($first, $consumer);
        }
        if ($this->groupAware->hasGroup($this->group->nameOrIndex())) {
            return $this->notMatchedOptional($first);
        }
        throw new NonexistentGroupException($this->group);
    }

    private function matched(RawMatchOffset $first): bool
    {
        return $first->hasGroup($this->group->nameOrIndex()) && $first->getGroup($this->group->nameOrIndex()) !== null;
    }

    private function matchedOptional(RawMatchOffset $match, callable $consumer): PresentOptional
    {
        $signatures = new PerformanceSignatures($match, $this->groupAware);
        $facade = new GroupFacadeMatched($this->base,
            new MatchGroupFactoryStrategy(),
            new LazyMatchAllFactory($this->base),
            new FirstNamedGroup($signatures),
            $signatures);
        $false = new FalseNegative($match);
        return new PresentOptional($consumer($facade->createGroup($this->group, $false, $false)));
    }

    private function notMatchedOptional(RawMatchOffset $first): Optional
    {
        if ($first->matched()) {
            return new GroupEmptyOptional($this->groupAware, $this->base, $this->group);
        }
        return new SubjectEmptyOptional($this->groupAware, $this->base, new FromFirstMatchMessage($this->group));
    }
}
