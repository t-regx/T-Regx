<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\SafeRegex\preg;

class LazyRawWithGroups implements GroupAware
{
    /** @var Base */
    private $base;
    /** @var GroupAware|null */
    private $match = null;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }

    public function hasGroup($nameOrIndex): bool
    {
        return $this->match()->hasGroup($nameOrIndex);
    }

    public function getGroupKeys(): array
    {
        return $this->match()->getGroupKeys();
    }

    private function match(): GroupAware
    {
        $this->match = $this->match ?? $this->rawMatches();
        return $this->match;
    }

    private function rawMatches(): RawMatches
    {
        preg::match_all($this->base->getPattern()->pattern, '', $matches);
        return new RawMatches($matches);
    }
}
