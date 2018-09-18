<?php
namespace CleanRegex\Match\Groups\Strategy;

use SafeRegex\preg;

class MatchAllGroupVerifier implements GroupVerifier
{
    public function groupExists(string $pattern, $nameOrIndex): bool
    {
        $matches = [];
        preg::match_all($pattern, '', $matches, PREG_PATTERN_ORDER);
        return array_key_exists($nameOrIndex, $matches);
    }
}
