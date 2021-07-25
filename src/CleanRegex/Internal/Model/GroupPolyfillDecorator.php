<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
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

    public function getText(): string
    {
        return $this->match->getText();
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        if (!$this->match->hasGroup($nameOrIndex)) {
            $this->polyfillGroups();
        }
        return $this->match->isGroupMatched($nameOrIndex);
    }

    public function getGroup($nameOrIndex): ?string
    {
        if ($this->match->hasGroup($nameOrIndex)) {
            return $this->read($nameOrIndex);
        }
        $this->polyfillGroups();
        return $this->read($nameOrIndex);
    }

    private function read($nameOrIndex): ?string
    {
        [$text, $offset] = $this->match->getGroupTextAndOffset($nameOrIndex);
        if ($offset === -1) {
            return null;
        }
        return $text;
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        if (!$this->match->hasGroup($nameOrIndex)) {
            $this->polyfillGroups();
        }
        return $this->match->getGroupTextAndOffset($nameOrIndex);
    }

    public function byteOffset(): int
    {
        return $this->match->byteOffset();
    }

    public function getGroupsTexts(): array
    {
        $this->polyfillGroups();
        return $this->match->getGroupsTexts();
    }

    public function getGroupsOffsets(): array
    {
        $this->polyfillGroups();
        return $this->match->getGroupsOffsets();
    }

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
