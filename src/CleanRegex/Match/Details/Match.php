<?php
namespace CleanRegex\Match\Details;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use CleanRegex\Internal\GroupNameValidator;
use function array_filter;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_slice;
use function array_values;
use function is_int;
use function is_string;

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
        $this->index = $index;
        $this->matches = $matches;
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
     * @return string|null
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex): ?string
    {
        $this->validateGroupName($nameOrIndex);

        if ($this->hasGroup($nameOrIndex)) {
            list($match, $offset) = $this->matches[$nameOrIndex][$this->index];
            return $match;
        }

        throw new NonexistentGroupException($nameOrIndex);
    }

    /**
     * @return string[]
     */
    public function namedGroups(): array
    {
        $namedGroups = [];

        foreach ($this->matches as $groupNameOrIndex => $match) {
            if (is_string($groupNameOrIndex)) {
                list($value, $offset) = $match[$this->index];
                $namedGroups[$groupNameOrIndex] = $value;
            }
        }

        return $namedGroups;
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return array_values(array_filter(array_keys($this->matches), function ($key) {
            return is_string($key);
        }));
    }

    /**
     * @return string[]
     */
    public function groups(): array
    {
        $indexMatches = array_filter($this->matches, function (array $match, $groupIndexOrName) {
            return is_int($groupIndexOrName);
        }, ARRAY_FILTER_USE_BOTH);

        $indexGroups = array_map(function (array $match) {
            list($value, $offset) = $match[$this->index];
            return $value;
        }, $indexMatches);

        return array_slice($indexGroups, 1);
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

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function matched($nameOrIndex): bool
    {
        return $this->group($nameOrIndex) !== null;
    }

    public function all(): array
    {
        return $this->getFirstFromAllMatches();
    }

    protected function getFirstFromAllMatches(): array
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

    private function validateGroupName($nameOrIndex): void
    {
        (new GroupNameValidator($nameOrIndex))->validate();
    }
}
