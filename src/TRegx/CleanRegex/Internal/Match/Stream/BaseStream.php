<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Exception\NoFirstSwitcherException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class BaseStream implements Stream, MatchAllFactory
{
    /** @var Base */
    private $base;

    /** @var IRawMatchesOffset */
    private $matches = null;
    /** @var IRawMatchOffset */
    private $match = null;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }

    public function all(): IRawMatchesOffset
    {
        return $this->getRawMatches();
    }

    public function first(): IRawMatchOffset
    {
        $this->match = $this->match ?? $this->getMatch();
        if ($this->match->matched()) {
            return $this->match;
        }
        throw new NoFirstSwitcherException();
    }

    private function getMatch(): IRawMatchOffset
    {
        if ($this->matches !== null) {
            return new RawMatchesToMatchAdapter($this->matches, 0);
        }
        return $this->base->matchOffset();
    }

    public function getRawMatches(): IRawMatchesOffset
    {
        $this->matches = $this->matches ?? $this->base->matchAllOffsets();
        return $this->matches;
    }

    public function firstKey(): int
    {
        return 0;
    }
}
