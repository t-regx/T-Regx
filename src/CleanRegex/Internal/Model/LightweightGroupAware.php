<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\SafeRegex\preg;

class LightweightGroupAware implements GroupAware
{
    /** @var Definition */
    private $definition;
    /** @var array|null */
    private $matches = null;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function hasGroup(GroupKey $group): bool
    {
        return \array_key_exists($group->nameOrIndex(), $this->matches());
    }

    public function getGroupKeys(): array
    {
        return \array_keys($this->matches());
    }

    private function matches(): array
    {
        if ($this->matches === null) {
            preg::match_all($this->definition->pattern, '', $this->matches);
        }
        return $this->matches;
    }
}
