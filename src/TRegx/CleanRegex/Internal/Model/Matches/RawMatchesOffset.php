<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Factory\MatchObjectFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Details\Match;
use function array_intersect_key;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_slice;
use function array_values;
use function is_array;
use function is_string;

class RawMatchesOffset implements IRawMatchesOffset
{
    private const GROUP_WHOLE_MATCH = 0;
    private const FIRST_MATCH = 0;

    /** @var array */
    private $matches;

    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    public function matched(): bool
    {
        return count($this->matches[self::GROUP_WHOLE_MATCH]) > 0;
    }

    /**
     * @return (string|null)[]
     */
    public function getAll(): array
    {
        return array_map(function ($match) {
            [$text, $offset] = $match;
            return $text;
        }, $this->matches[self::GROUP_WHOLE_MATCH]);
    }

    public function getMatchObjects(MatchObjectFactory $factory): array
    {
        $matchObjects = [];
        foreach ($this->matches[self::GROUP_WHOLE_MATCH] as $index => $firstWhole) {
            $match = array_map(function ($match) use ($index) {
                return $match[$index];
            }, $this->matches);
            $matchObjects[] = $factory->create($index, new RawMatchOffset($match), new EagerMatchAllFactory($this));
        }
        return $matchObjects;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return array_key_exists($nameOrIndex, $this->matches);
    }

    public function getLimitedGroupOffsets($nameOrIndex, int $limit)
    {
        return $this->mapToOffset($this->getLimitedGroups($nameOrIndex, $limit));
    }

    private function getLimitedGroups($nameOrIndex, int $limit)
    {
        $match = $this->matches[$nameOrIndex];
        if ($limit === -1) {
            return $match;
        }
        return array_slice($match, 0, $limit);
    }

    private function mapToOffset(array $matches): array
    {
        return array_map([$this, 'mapMatch'], $matches);
    }

    public function getFirstMatchObject(MatchObjectFactory $factory): Match
    {
        return $factory->create(
            self::FIRST_MATCH,
            new RawMatchesToMatchAdapter($this, self::FIRST_MATCH),
            new EagerMatchAllFactory($this)
        );
    }

    private function mapMatch($match): ?int
    {
        if ($match === null || is_string($match)) {
            return null;
        }
        if (!is_array($match)) {
            throw new InternalCleanRegexException();
        }
        [$text, $offset] = $match;
        if ($offset === -1) {
            return null;
        }
        return $offset;
    }

    public function getOffset(int $index): int
    {
        [$text, $offset] = $this->matches[self::GROUP_WHOLE_MATCH][$index];
        return $offset;
    }

    public function getTextAndOffset(int $index): array
    {
        return $this->matches[self::GROUP_WHOLE_MATCH][$index];
    }

    public function getGroupTextAndOffset($nameOrIndex, int $index): array
    {
        return $this->matches[$nameOrIndex][$index];
    }

    public function getGroupKeys(): array
    {
        return array_keys($this->matches);
    }

    /**
     * @param int $index
     * @return (int|null)[]
     */
    public function getGroupsOffsets(int $index): array
    {
        return array_map(function (array $match) use ($index) {
            [$text, $offset] = $match[$index];
            return $offset;
        }, $this->matches);
    }

    /**
     * @param int $index
     * @return (string|null)[]
     */
    public function getGroupsTexts(int $index): array
    {
        return array_map(function (array $match) use ($index) {
            [$text, $offset] = $match[$index];
            return $text;
        }, $this->matches);
    }

    public function getGroupTexts($group): array
    {
        return array_map(function ($group) {
            [$text, $offset] = $group;
            return $text;
        }, $this->matches[$group]);
    }

    public function isGroupMatched($nameOrIndex, int $index): bool
    {
        $var = $this->matches[$nameOrIndex][$index];
        if (is_array($var)) {
            return $var[1] !== -1;
        }
        return false;
    }

    public function getRawMatchOffset(int $index): RawMatchOffset
    {
        $matches = array_map(function (array $match) use ($index) {
            return $match[$index];
        }, $this->matches);
        return new RawMatchOffset($matches);
    }

    public function getRawMatch(int $index): RawMatch
    {
        return new RawMatch(array_map(function (array $match) use ($index) {
            [$text, $offset] = $match[$index];
            return $text;
        }, $this->matches));
    }

    public function filterMatchesByMatchObjects(Predicate $predicate, MatchObjectFactory $factory): array
    {
        $matchObjects = $this->getMatchObjects($factory);
        $filteredMatches = array_filter($matchObjects, [$predicate, 'test']);

        return array_map(function (array $match) use ($filteredMatches) {
            return array_values(array_intersect_key($match, $filteredMatches));
        }, $this->matches);
    }
}
