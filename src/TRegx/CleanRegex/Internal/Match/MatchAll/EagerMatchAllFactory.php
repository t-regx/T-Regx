<?php
namespace TRegx\CleanRegex\Internal\Match\MatchAll;

use TRegx\CleanRegex\Internal\Model\IRawMatches;

class EagerMatchAllFactory implements MatchAllFactory
{
    /** @var IRawMatches */
    private $matches;

    public function __construct(IRawMatches $matches)
    {
        $this->matches = $matches;
    }

    public function getRawMatches(): IRawMatches
    {
        return $this->matches;
    }
}
