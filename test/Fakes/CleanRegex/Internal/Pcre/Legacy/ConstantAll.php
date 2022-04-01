<?php
namespace Test\Fakes\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;

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
