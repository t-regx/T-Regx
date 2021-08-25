<?php
namespace Test\Utils\Impl;

use AssertionError;

class GroupDetail extends ThrowDetail
{
    /** @var bool[] */
    private $matchedGroups;

    public function __construct(array $matchedGroups)
    {
        $this->matchedGroups = $matchedGroups;
    }

    public function matched($nameOrIndex): bool
    {
        if ($this->hasGroup($nameOrIndex)) {
            return $this->matchedGroups[$nameOrIndex];
        }
        throw new AssertionError("Failed to assert that an existing group was matched");
    }

    public function hasGroup($nameOrIndex): bool
    {
        return \array_key_exists($nameOrIndex, $this->matchedGroups);
    }
}
