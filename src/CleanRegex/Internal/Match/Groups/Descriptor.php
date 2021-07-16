<?php
namespace TRegx\CleanRegex\Internal\Match\Groups;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\SafeRegex\preg;

class Descriptor
{
    /** @var Definition */
    private $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function getGroups(): array
    {
        preg::match_all($this->definition->pattern, '', $matches);
        return \array_keys($matches);
    }
}
