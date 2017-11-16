<?php
namespace CleanRegex\Match;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;

class Match
{
    /** @var integer */
    protected const WHOLE_MATCH = 0;

    /** @var string */
    protected $subject;
    /** @var int */
    protected $index;
    /** @var array */
    protected $matches;

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
        list($match, $offset) = $this->matches[self::WHOLE_MATCH][$this->index];
        return $match;
    }

    /**
     * @param string|int $nameOrIndex
     * @return string
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex): string
    {
        $this->validateGroupName($nameOrIndex);

        if ($this->hasGroup($nameOrIndex)) {
            list($match, $offset) = $this->matches[$nameOrIndex][$this->index];
            return $match;
        }

        throw new NonexistentGroupException();
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

    public function all(): array
    {
        return array_map(function ($match) {
            list($value, $offset) = $match;
            return $value;
        }, $this->matches[self::WHOLE_MATCH]);
    }

    public function offset(): int
    {
        list($value, $offset) = $this->matches[self::WHOLE_MATCH][$this->index];
        return $offset;
    }

    public function __toString(): string
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
