<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy\Prime;

use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;

class MatchesFirstUsedForGroup implements UsedForGroup
{
    /** @var RawMatchesOffset */
    private $matches;

    public function __construct(RawMatchesOffset $matches)
    {
        $this->matches = $matches;
    }

    public function isGroupMatched($nameOrIndex): bool
    {
        return $this->matches->isGroupMatched($nameOrIndex, 0);
    }

    public function getGroupTextAndOffset($nameOrIndex): array
    {
        return $this->matches->getGroupTextAndOffset($nameOrIndex, 0);
    }
}
