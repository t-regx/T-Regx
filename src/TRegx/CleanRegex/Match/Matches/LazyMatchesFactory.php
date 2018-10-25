<?php
namespace TRegx\CleanRegex\Match\Matches;

use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;
use TRegx\SafeRegex\preg;

class LazyMatchesFactory implements MatchesFactory
{
    /** @var ApiBase */
    private $base;

    /** @var null|array */
    private $matches = null;

    public function __construct(ApiBase $base)
    {
        $this->base = $base;
    }

    public function getMatches(): RawMatchesOffset
    {
        if ($this->matches === null) {
            $this->matches = $this->matchAll();
        }
        return new RawMatchesOffset($this->matches, $this->base);
    }

    private function matchAll(): array
    {
        preg::match_all($this->base->getPattern()->pattern, $this->base->getSubject(), $matches);
        return $matches;
    }
}
