<?php
namespace Danon\CleanRegex\Match;

class Match
{
    /** @var string */
    private $subject;
    /** @var int */
    private $index;
    /** @var array */
    private $matches;

    public function __construct(string $subject, int $index, array $matches)
    {
        $this->subject = $subject;
        $this->matches = $matches;
        $this->index = $index;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function match(): string
    {
        list($match, $offset) = $this->matches[0][$this->index];
        return $match;
    }

    /**
     * @param string|int $nameOrIndex
     * @return string
     */
    public function group($nameOrIndex): string
    {
        $this->validateGroupName($nameOrIndex);

        if ($this->hasGroup($nameOrIndex)) {
            list($match, $offset) = $this->matches[$nameOrIndex][$this->index];
            return $match;
        }

        return null;
    }

    public function namedGroups(): array
    {
        $namedGroups = [];

        foreach ($this->matches as $index => $match) {
            if (is_string($index)) {
                list($value, $offset) = $match[$this->index];
                $namedGroups[$index] = $value;
            }
        }

        return $namedGroups;
    }

    public function groupNames(): array
    {
        return array_values(array_filter(array_keys($this->matches), function ($key) {
            return is_string($key);
        }));
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        $this->validateGroupName($nameOrIndex);

        return array_key_exists($nameOrIndex, $this->matches);
    }

    public function offset(): int
    {
        list($value, $offset) = $this->matches[0][$this->index];
        return $offset;
    }

    function __toString(): string
    {
        return $this->match();
    }

    private function validateGroupName($nameOrIndex)
    {
        if (!is_string($nameOrIndex) && !is_int($nameOrIndex)) {
            throw new \InvalidArgumentException("Group index can only be an integer or string");
        }
    }
}
