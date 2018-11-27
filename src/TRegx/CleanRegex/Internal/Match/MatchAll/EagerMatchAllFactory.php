<?php
namespace TRegx\CleanRegex\Internal\Match\MatchAll;

use TRegx\CleanRegex\Internal\Model\IRawMatchesOffset;

class EagerMatchAllFactory implements MatchAllFactory
{
    /** @var IRawMatchesOffset */
    private $matches;

    public function __construct(IRawMatchesOffset $matches)
    {
        $this->matches = $matches;
    }

    public function getRawMatches(): IRawMatchesOffset
    {
        return $this->matches;
    }
}
