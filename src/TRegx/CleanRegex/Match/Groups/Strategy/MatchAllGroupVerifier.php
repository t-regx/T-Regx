<?php
namespace TRegx\CleanRegex\Match\Groups\Strategy;

use TRegx\SafeRegex\preg;

class MatchAllGroupVerifier implements GroupVerifier
{
    public function groupExists(string $pattern, $nameOrIndex): bool
    {
        preg::match_all($pattern, '', $matches, PREG_PATTERN_ORDER);
        return array_key_exists($nameOrIndex, $matches);
    }
}
