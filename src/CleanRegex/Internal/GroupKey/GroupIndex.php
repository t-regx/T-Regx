<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use InvalidArgumentException;

class GroupIndex extends GroupKey
{
    /** @var int */
    private $index;

    public function __construct(int $index)
    {
        $this->index = $index;
    }

    public function nameOrIndex(): int
    {
        if ($this->index < 0) {
            throw new InvalidArgumentException("Group index must be a non-negative integer, but $this->index given");
        }
        return $this->index;
    }

    public function full(): bool
    {
        return $this->index === 0;
    }

    public function __toString(): string
    {
        return "#$this->index";
    }
}
