<?php
namespace TRegx\CleanRegex\Internal;

class Splits
{
    /** @var int */
    private $splits;

    public function __construct(int $splits)
    {
        if ($splits < 0) {
            throw new \InvalidArgumentException("Negative splits: $splits");
        }
        $this->splits = $splits;
    }

    public function elements(): int
    {
        return $this->splits + 1;
    }

    public function intValue(): int
    {
        return $this->splits;
    }
}
