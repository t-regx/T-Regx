<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy\Prime;

use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;

class MatchUsedForGroup implements UsedForGroup
{
    /** @var RawMatchOffset */
    private $matchOffset;

    public function __construct(RawMatchOffset $matchOffset)
    {
        $this->matchOffset = $matchOffset;
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        return $this->matchOffset->isGroupMatched($nameOrIndex);
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->matchOffset->getGroupTextAndOffset($nameOrIndex);
    }
}
