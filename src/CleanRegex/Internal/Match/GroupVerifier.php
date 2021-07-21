<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\SafeRegex\preg;

class GroupVerifier
{
    /** @var Definition */
    private $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function groupExists($nameOrIndex): bool
    {
        preg::match_all($this->definition->pattern, '', $matches);
        return \in_array($nameOrIndex, \array_keys($matches), true);
    }
}
