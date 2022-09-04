<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

/**
 * @deprecated
 */
class RawMatchesToMatchAdapter implements IRawMatchOffset
{
    /** @var RawMatchesOffset */
    private $matches;
    /** @var int */
    private $index;

    public function __construct(RawMatchesOffset $matches, int $index)
    {
        $this->matches = $matches;
        $this->index = $index;
    }

    public function text(): string
    {
        $all = $this->matches->getTexts();
        return $all[$this->index];
    }

    public function hasGroup(GroupKey $group): bool
    {
        return $this->matches->hasGroup($group);
    }

    public function getGroupKeys(): array
    {
        return $this->matches->getGroupKeys();
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        return $this->matches->isGroupMatched($nameOrIndex, $this->index);
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->matches->getGroupTextAndOffset($nameOrIndex, $this->index);
    }

    public function byteOffset(): int
    {
        return $this->matches->getOffset($this->index);
    }
}
