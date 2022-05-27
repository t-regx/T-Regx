<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;

class FalseNegative implements Entry, UsedForGroup
{
    /** @var RawMatchOffset */
    private $match;

    public function __construct(RawMatchOffset $match)
    {
        $this->match = $match;
    }

    public function maybeGroupIsMissing($nameOrIndex): bool
    {
        return !$this->match->hasGroup(GroupKey::of($nameOrIndex));
    }

    public function text(): string
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
}
