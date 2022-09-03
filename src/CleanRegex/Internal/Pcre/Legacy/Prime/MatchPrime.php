<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy\Prime;

use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;

class MatchPrime implements Prime
{
    /** @var RawMatchOffset */
    private $match;

    public function __construct(RawMatchOffset $match)
    {
        $this->match = $match;
    }

    public function firstUsedForGroup(): UsedForGroup
    {
        return new MatchUsedForGroup($this->match);
    }
}
