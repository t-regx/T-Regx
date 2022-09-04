<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

/**
 * @deprecated
 */
class MatchAllFactoryMatchOffset implements IRawMatchOffset
{
    /** @var MatchAllFactory */
    private $factory;
    /** @var int */
    private $index;

    public function __construct(MatchAllFactory $factory, int $index)
    {
        $this->factory = $factory;
        $this->index = $index;
    }

    public function text(): string
    {
        $all = $this->matchesOffset()->getTexts();
        return $all[$this->index];
    }

    public function hasGroup(GroupKey $group): bool
    {
        return $this->matchesOffset()->hasGroup($group);
    }

    public function getGroupKeys(): array
    {
        return $this->matchesOffset()->getGroupKeys();
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        return $this->matchesOffset()->isGroupMatched($nameOrIndex, $this->index);
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->matchesOffset()->getGroupTextAndOffset($nameOrIndex, $this->index);
    }

    public function byteOffset(): int
    {
        return $this->matchesOffset()->getOffset($this->index);
    }

    private function matchesOffset(): RawMatchesOffset
    {
        return $this->factory->getRawMatches();
    }
}
