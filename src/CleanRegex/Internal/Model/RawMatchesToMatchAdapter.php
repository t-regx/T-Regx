<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

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

    public function hasGroup($nameOrIndex): bool
    {
        return $this->matches->hasGroup($nameOrIndex);
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

    public function getGroupsTexts(): array
    {
        return $this->matches->getGroupsTexts($this->index);
    }

    public function getGroupsOffsets(): array
    {
        return $this->matches->getGroupsOffsets($this->index);
    }
}
