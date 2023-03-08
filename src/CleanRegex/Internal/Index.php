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

    /**
     * @param array<mixed> $array
     * @return bool
     */
    public function in(array $array): bool
    {
        return \array_key_exists($this->index, $array);
    }

    /**
     * @template T
     * @param T[] $array
     * @return T
     */
    public function valueFrom(array $array)
    {
        return $array[$this->index];
    }

    public function __toString(): string
    {
        return (string) $this->index;
    }
}
