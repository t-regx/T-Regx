<?php
namespace TRegx\CleanRegex\Internal\Number;

class Base
{
    /** @var int */
    private $base;

    public function __construct(int $base)
    {
        $this->base = $base;
    }

    public function base(): int
    {
        if ($this->base < 2 || $this->base > 36) {
            throw new \InvalidArgumentException("Invalid base: $this->base (supported bases 2-36, case-insensitive)");
        }
        return $this->base;
    }
}
