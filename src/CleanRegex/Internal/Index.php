<?php
namespace TRegx\CleanRegex\Internal;

class Index
{
    /** @var int */
    private $index;

    public function __construct(int $index)
    {
        if ($index < 0) {
            throw new \InvalidArgumentException("Negative index: $index");
        }
        $this->index = $index;
    }

    public function in(array $array): bool
    {
        return \array_key_exists($this->index, $array);
    }

    public function valueFrom(array $array)
    {
        return $array[$this->index];
    }

    public function __toString(): string
    {
        return $this->index;
    }
}
