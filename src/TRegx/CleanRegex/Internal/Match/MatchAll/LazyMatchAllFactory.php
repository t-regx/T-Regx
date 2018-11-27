<?php
namespace TRegx\CleanRegex\Internal\Match\MatchAll;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\IRawMatchesOffset;

class LazyMatchAllFactory implements MatchAllFactory
{
    /** @var Base */
    private $base;
    /** @var IRawMatchesOffset|null */
    private $value;

    public function __construct(Base $base)
    {
        $this->base = $base;
        $this->value = null;
    }

    public function getRawMatches(): IRawMatchesOffset
    {
        if ($this->value === null) {
            $this->value = $this->base->matchAllOffsets();
        }
        return $this->value;
    }
}
