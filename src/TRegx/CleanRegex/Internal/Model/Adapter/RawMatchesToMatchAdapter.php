<?php
namespace TRegx\CleanRegex\Internal\Model\Adapter;

use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatches;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class RawMatchesToMatchAdapter implements IRawMatchOffset
{
    /** @var IRawMatches */
    private $matches;
    /** @var int */
    private $index;

    public function __construct(IRawMatchesOffset $matches, int $index)
    {
        $this->matches = $matches;
        $this->index = $index;
    }

    public function matched(): bool
    {
        return $this->matches->matched();
    }

    public function getText(): string
    {
        $all = $this->matches->getTexts();
        return $all[$this->index];
    }

    public function hasGroup($nameOrIndex): bool
    {
        return $this->matches->hasGroup($nameOrIndex);
    }

    /**
     * @return (string|int)[]
     */
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

    /**
     * @return (string|null)[]
     */
    public function getGroupsTexts(): array
    {
        return $this->matches->getGroupsTexts($this->index);
    }

    /**
     * @return (int|null)[]
     */
    public function getGroupsOffsets(): array
    {
        return $this->matches->getGroupsOffsets($this->index);
    }
}
