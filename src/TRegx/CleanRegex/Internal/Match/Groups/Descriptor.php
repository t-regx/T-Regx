<?php
namespace TRegx\CleanRegex\Internal\Match\Groups;

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

    public function getGroups(): array
    {
        preg::match_all($this->pattern->pattern, '', $matches, \PREG_PATTERN_ORDER);
        return \array_keys($matches);
    }
}
