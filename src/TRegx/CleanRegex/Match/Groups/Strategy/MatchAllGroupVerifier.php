<?php
namespace TRegx\CleanRegex\Match\Groups\Strategy;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;

class MatchAllGroupVerifier implements GroupVerifier
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function groupExists($nameOrIndex): bool
    {
        preg::match_all($this->pattern->pattern, '', $matches, PREG_PATTERN_ORDER);
        return array_key_exists($nameOrIndex, $matches);
    }
}
