<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\SafeRegex\preg;

class LazyRawWithGroups implements IRawWithGroups
{
    /** @var Base */
    private $base;

    /** @var IRawWithGroups|null */
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

    private function match(): IRawWithGroups
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
