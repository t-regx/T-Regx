<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Model\GroupAware;

class GroupKeys implements GroupAware
{
    /** @var array */
    private $keys;

    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    public function getGroupKeys(): array
    {
        return $this->keys;
    }

    public function hasGroup($nameOrIndex): bool
    {
        return \in_array($nameOrIndex, $this->keys);
    }
}
