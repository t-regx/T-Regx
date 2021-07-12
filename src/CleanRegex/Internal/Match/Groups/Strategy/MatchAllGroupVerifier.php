<?php
namespace TRegx\CleanRegex\Internal\Match\Groups\Strategy;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\Groups\Descriptor;
use function in_array;

class MatchAllGroupVerifier implements GroupVerifier
{
    /** @var Definition */
    private $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function groupExists($nameOrIndex): bool
    {
        return in_array($nameOrIndex, (new Descriptor($this->definition))->getGroups(), true);
    }
}
