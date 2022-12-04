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
        if ($this->index < 0) {
            throw new InvalidArgumentException("Group index must be a non-negative integer, but $this->index given");
        }
    }

    public function nameOrIndex(): int
    {
        return $this->index;
    }

    public function __toString(): string
    {
        return "#$this->index";
    }
}
