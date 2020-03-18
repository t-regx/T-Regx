<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class BaseSwitcher implements Switcher
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
        if ($this->matches === null) {
            $this->matches = $this->base->matchAllOffsets();
        }
        return $this->matches;
    }

    public function first(): IRawMatchOffset
    {
        if ($this->match === null) {
            if ($this->matches !== null) {
                return new RawMatchesToMatchAdapter($this->matches, 0);
            }
            $this->match = $this->base->matchOffset();
        }
        return $this->match;
    }
}
