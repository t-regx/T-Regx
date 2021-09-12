<?php
namespace Test\Fakes\CleanRegex\Internal\Match\MatchAll;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

class ConstantAll implements MatchAllFactory
{
    /** @var array */
    private $matches;

    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    public function getRawMatches(): RawMatchesOffset
    {
        return new RawMatchesOffset($this->matches);
    }
}
