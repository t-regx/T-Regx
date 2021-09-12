<?php
namespace Test\Fakes\CleanRegex\Match\Details\Group;

use AssertionError;

class IsIntGroup extends ThrowGroup
{
    /** @var bool */
    private $isInt;
    /** @var int */
    private $base;

    public function __construct(bool $isInt, ?int $base)
    {
        $this->isInt = $isInt;
        $this->base = $base;
    }

    public function isInt(int $base = null): bool
    {
        if ($base === $this->base) {
            return $this->isInt;
        }
        throw new AssertionError('Failed to assert that Group checked as integer with proper base');
    }
}
