<?php
namespace Danon\CleanRegex\Match;

class Match
{
    /** @var string */
    private $first;
    /** @var int */
    private $index;
    /** @var string */
    private $match;
    /** @var array */
    private $matches;
    /** @var int */
    private $offset;

    public function __construct(string $first, int $index, string $match, int $offset, array $allMatches)
    {
        $this->first = $first;
        $this->match = $match;
        $this->matches = $allMatches;
        $this->offset = $offset;
        $this->index = $index;
    }

    public function subject(): string
    {
        return $this->first;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function get(): string
    {
        return $this->match;
    }

    public function all(): array
    {
        return $this->matches;
    }

    public function offset(): int
    {
        return $this->offset;
    }
}
