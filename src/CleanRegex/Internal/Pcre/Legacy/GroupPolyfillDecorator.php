<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;

/**
 * @deprecated
 */
class GroupPolyfillDecorator implements IRawMatchOffset
{
    /** @var FalseNegative */
    private $falseMatch;
    /** @var IRawMatchOffset */
    private $trueMatch;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var int */
    private $newMatchIndex;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(FalseNegative $match, MatchAllFactory $allFactory, int $newMatchIndex, GroupAware $groupAware = null)
    {
        $this->falseMatch = $match;
        $this->trueMatch = null;
        $this->allFactory = $allFactory;
        $this->newMatchIndex = $newMatchIndex;
        $this->groupAware = $groupAware ?? new FactoryGroupAware($allFactory);
    }

    public function hasGroup(GroupKey $group): bool
    {
        if ($this->falseMatch->maybeGroupIsMissing($group->nameOrIndex())) {
            return $this->reloadAndHasGroup($group);
        }
        return true;
    }

    private function reloadAndHasGroup(GroupKey $group): bool
    {
        if ($this->trueMatch !== null) {
            return $this->trueMatch->hasGroup($group);
        }
        return $this->groupAware->hasGroup($group);
    }

    public function text(): string
    {
        return $this->falseMatch->text();
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        if ($this->falseMatch->maybeGroupIsMissing($nameOrIndex)) {
            return false;
        }
        return $this->falseMatch->isGroupMatched($nameOrIndex);
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        if ($this->falseMatch->maybeGroupIsMissing($nameOrIndex)) {
            return $this->trueMatch()->getGroupTextAndOffset($nameOrIndex);
        }
        return $this->falseMatch->getGroupTextAndOffset($nameOrIndex);
    }

    public function byteOffset(): int
    {
        return $this->falseMatch->byteOffset();
    }

    public function getGroupKeys(): array
    {
        if ($this->trueMatch !== null) {
            return $this->trueMatch->getGroupKeys();
        }
        return $this->groupAware->getGroupKeys();
    }

    private function trueMatch(): IRawMatchOffset
    {
        if ($this->trueMatch === null) {
            $this->trueMatch = new RawMatchesToMatchAdapter($this->allFactory->getRawMatches(), $this->newMatchIndex);
        }
        return $this->trueMatch;
    }
}
