<?php
namespace TRegx\CleanRegex\Internal\Match\MatchAll;

use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

class EagerMatchAllFactory implements MatchAllFactory
{
    /** @var RawMatchesOffset */
    private $matches;

    public function __construct(RawMatchesOffset $matches)
    {
        $this->matches = $matches;
    }

    public function getRawMatches(): RawMatchesOffset
    {
        return $this->matches;
    }
}
