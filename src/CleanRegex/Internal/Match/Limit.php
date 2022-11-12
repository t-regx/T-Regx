<?php
namespace TRegx\CleanRegex\Internal\Match;

class Limit
{
    /** @var int */
    private $limit;

    public function __construct(int $limit)
    {
        if ($limit < 0) {
            throw new \InvalidArgumentException("Negative limit: $limit");
        }
        $this->limit = $limit;
    }

    public function intValue(): int
    {
        return $this->limit;
    }

    public function empty(): bool
    {
        return $this->limit === 0;
    }
}
