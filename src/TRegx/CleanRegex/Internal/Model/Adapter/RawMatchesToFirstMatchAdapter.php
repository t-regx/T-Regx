<?php
namespace TRegx\CleanRegex\Internal\Model\Adapter;

use TRegx\CleanRegex\Internal\Model\IRawMatches;
use TRegx\CleanRegex\Internal\Model\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;

class RawMatchesToFirstMatchAdapter implements IRawMatchOffset
{
    /** @var IRawMatches */
    private $matches;

    public function __construct(IRawMatchesOffset $matches)
    {
        $this->matches = $matches;
    }

    public function matched(): bool
    {
        return $this->matches->matched();
    }

    public function getMatch(): string
    {
        $all = $this->matches->getAll();
        return $all[0];
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
        return $this->matches->isGroupMatched($nameOrIndex, 0);
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->matches->getGroupTextAndOffset($nameOrIndex, 0);
    }

    public function byteOffset(): int
    {
        return $this->matches->getOffset(0);
    }

    /**
     * @return (string|null)[]
     */
    public function getGroupsTexts(): array
    {
        return $this->matches->getGroupsTexts(0);
    }

    /**
     * @return (int|null)[]
     */
    public function getGroupsOffsets(): array
    {
        return $this->matches->getGroupsOffsets(0);
    }
}
