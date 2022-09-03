<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy\Prime;

use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;

class MatchesFirstPrime implements Prime
{
    /** @var RawMatchesOffset */
    private $matches;

    public function __construct(RawMatchesOffset $matches)
    {
        $this->matches = $matches;
    }

    public function firstUsedForGroup(): UsedForGroup
    {
        return new MatchesFirstUsedForGroup($this->matches);
    }
}
