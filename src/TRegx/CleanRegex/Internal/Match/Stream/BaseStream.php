<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class BaseStream implements Stream, MatchAllFactory
{
    /** @var Base */
    private $base;

    /** @var RawMatchesOffset */
    private $matches = null;
    /** @var IRawMatchOffset */
    private $match = null;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }

    public function all(): RawMatchesOffset
    {
        return $this->getRawMatches();
    }

    public function first(): IRawMatchOffset
    {
        $this->match = $this->match ?? $this->getMatch();
        if ($this->match->matched()) {
            return $this->match;
        }
        throw new NoFirstStreamException();
    }

    private function getMatch(): IRawMatchOffset
    {
        if ($this->matches !== null) {
            return new RawMatchesToMatchAdapter($this->matches, 0);
        }
        return $this->base->matchOffset();
    }

    public function getRawMatches(): RawMatchesOffset
    {
        $this->matches = $this->matches ?? $this->base->matchAllOffsets();
        return $this->matches;
    }

    public function firstKey(): int
    {
        return 0;
    }
}
