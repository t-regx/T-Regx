<?php
namespace TRegx\CleanRegex\Internal\Number;

class Base
{
    /** @var int */
    private $base;

    public function __construct(?int $base)
    {
        $this->base = $base;
        if ($this->base !== null && ($this->base < 2 || $this->base > 36)) {
            throw new \InvalidArgumentException("Invalid base: $this->base (supported bases 2-36, case-insensitive)");
        }
    }

    public function base(): int
    {
        return $this->base ?? 10;
    }

    public function __toString(): string
    {
        return $this->base();
    }
}
