<?php
namespace Test\Fakes\CleanRegex\Match\Details\Group;

use AssertionError;

class IntGroup extends ThrowGroup
{
    /** @var int|null */
    private $int;
    /** @var int */
    private $base;

    public function __construct(int $int, ?int $base)
    {
        $this->int = $int;
        $this->base = $base;
    }

    public function toInt(int $base = null): int
    {
        if ($base === $this->base) {
            return $this->int;
        }
        throw new AssertionError('Failed to assert that Group checked integer with the given base');
    }
}
