<?php
namespace TRegx\CleanRegex\Match\Groups\Strategy;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Groups\Descriptor;

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
        $matches = (new Descriptor($this->pattern))->getGroups();
        return $this->arrayHasValue($matches, $nameOrIndex);
    }

    private function arrayHasValue(array $array, $needle): bool
    {
        return \array_search($needle, $array, true) !== false;
    }
}
