<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Match\Details\Match;

class ComputedMatchStrategy implements MatchRs
{
    /** @var callable */
    private $mapper;

    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function substituteGroup(Match $match): string
    {
        return \call_user_func($this->mapper, $match);
    }
}
