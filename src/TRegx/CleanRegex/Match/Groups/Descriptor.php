<?php
namespace TRegx\CleanRegex\Match\Groups;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;

class Descriptor
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function hasAnyGroup(): bool
    {
        return \count($this->getGroups()) > 1;
    }

    public function getGroups(): array
    {
        return \array_keys($this->getMatches());
    }

    private function getMatches(): array
    {
        preg::match_all($this->pattern->pattern, '', $matches, PREG_PATTERN_ORDER);
        return $matches;
    }
}
