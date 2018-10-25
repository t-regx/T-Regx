<?php
namespace TRegx\CleanRegex\Match\Matches;

use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;

class PredefinedMatchesFactory implements MatchesFactory
{
    /** @var RawMatchesOffset */
    private $matches;

    public function __construct(RawMatchesOffset $matches)
    {
        $this->matches = $matches;
    }

    public function getMatches(): RawMatchesOffset
    {
        return $this->matches;
    }
}
