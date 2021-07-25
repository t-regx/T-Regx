<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class FalseNegative implements IRawMatchOffset
{
    /** @var RawMatchOffset */
    private $match;

    public function __construct(RawMatchOffset $match)
    {
        $this->match = $match;
    }

    public function hasGroup($nameOrIndex): bool
    {
        return $this->match->hasGroup($nameOrIndex);
    }

    public function getGroupKeys(): array
    {
        return $this->match->getGroupKeys();
    }

    public function getText(): string
    {
        return $this->match->getText();
    }

    public function byteOffset(): int
    {
        return $this->match->byteOffset();
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        return $this->match->isGroupMatched($nameOrIndex);
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->match->getGroupTextAndOffset($nameOrIndex);
    }

    public function getGroupsTexts(): array
    {
        return $this->match->getGroupsTexts();
    }

    public function getGroupsOffsets(): array
    {
        return $this->match->getGroupsOffsets();
    }
}
