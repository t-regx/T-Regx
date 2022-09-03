<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy\Prime;

use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;

class MatchAllFactoryPrime implements Prime
{
    /** @var MatchAllFactory */
    private $factory;

    public function __construct(MatchAllFactory $factory)
    {
        $this->factory = $factory;
    }

    public function firstUsedForGroup(): UsedForGroup
    {
        return new MatchesFirstUsedForGroup($this->factory->getRawMatches());
    }
}
