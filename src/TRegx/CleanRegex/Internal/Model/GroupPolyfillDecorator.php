<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;

class GroupPolyfillDecorator implements IRawMatchOffset
{
    /** @var IRawMatchOffset */
    private $match;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var int */
    private $newMatchIndex;

    public function __construct(IRawMatchOffset $match, MatchAllFactory $allFactory, int $newMatchIndex)
    {
        $this->match = $match;
        $this->allFactory = $allFactory;
        $this->newMatchIndex = $newMatchIndex;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        if ($this->match->hasGroup($nameOrIndex)) {
            return true;
        }
        $this->polyfillGroups();
        return $this->match->hasGroup($nameOrIndex);
    }

    public function matched(): bool
    {
        return $this->match->matched();
    }

    public function getText(): string
    {
        return $this->match->getText();
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        $this->polyfillGroups();
        return $this->match->isGroupMatched($nameOrIndex);
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        $this->polyfillGroups();
        return $this->match->getGroupTextAndOffset($nameOrIndex);
    }

    public function byteOffset(): int
    {
        return $this->match->byteOffset();
    }

    /**
     * @return (string|null)[]
     */
    public function getGroupsTexts(): array
    {
        $this->polyfillGroups();
        return $this->match->getGroupsTexts();
    }

    /**
     * @return (int|null)[]
     */
    public function getGroupsOffsets(): array
    {
        $this->polyfillGroups();
        return $this->match->getGroupsOffsets();
    }

    /**
     * @return (string|int)[]
     */
    public function getGroupKeys(): array
    {
        $this->polyfillGroups();
        return $this->match->getGroupKeys();
    }

    private function polyfillGroups(): void
    {
        if ($this->match instanceof RawMatchesToMatchAdapter) {
            return;
        }
        $this->match = new RawMatchesToMatchAdapter($this->allFactory->getRawMatches(), $this->newMatchIndex);
    }
}
