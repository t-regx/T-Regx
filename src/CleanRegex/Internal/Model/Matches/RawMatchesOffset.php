<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

class RawMatchesOffset implements IRawMatches, IRawWithGroups
{
    private const GROUP_WHOLE_MATCH = 0;

    /** @var array */
    private $matches;

    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    public function matched(): bool
    {
        return \count($this->matches[self::GROUP_WHOLE_MATCH]) > 0;
    }

    public function getCount(): int
    {
        return \count($this->matches[self::GROUP_WHOLE_MATCH]);
    }

    public function getDetailObjects(DetailObjectFactory $factory): array
    {
        $matchObjects = [];
        foreach ($this->matches[self::GROUP_WHOLE_MATCH] as $index => $firstWhole) {
            $matchObjects[$index] = $factory->create($index, new RawMatchesToMatchAdapter($this, $index), new EagerMatchAllFactory($this));
        }
        return $matchObjects;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return \array_key_exists($nameOrIndex, $this->matches);
    }

    public function getLimitedGroupOffsets($nameOrIndex, int $limit): array
    {
        return $this->mapToOffset($this->getLimitedGroups($nameOrIndex, $limit));
    }

    private function getLimitedGroups($nameOrIndex, int $limit): array
    {
        $match = $this->matches[$nameOrIndex];
        if ($limit === -1) {
            return $match;
        }
        return \array_slice($match, 0, $limit);
    }

    private function mapToOffset(array $matches): array
    {
        return \array_values(\array_map([$this, 'mapMatch'], $matches));
    }

    private function mapMatch($match): ?int
    {
        if ($match === null || \is_string($match)) {
            return null;
        }
        if (!\is_array($match)) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
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

    public function getGroupTextAndOffsetAll($nameOrIndex): array
    {
        return $this->matches[$nameOrIndex];
    }

    public function getGroupKeys(): array
    {
        return \array_keys($this->matches);
    }

    /**
     * @param int $index
     * @return (int|null)[]
     */
    public function getGroupsOffsets(int $index): array
    {
        return \array_map(static function (array $match) use ($index) {
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
        return \array_map(static function (array $match) use ($index) {
            [$text, $offset] = $match[$index];
            return $text;
        }, $this->matches);
    }

    public function getGroupTexts($group): array
    {
        return \array_map(static function ($group) {
            [$text, $offset] = $group;
            if ($offset === -1) {
                return null;
            }
            return $text;
        }, $this->matches[$group]);
    }

    public function getTexts(): array
    {
        return $this->getGroupTexts(self::GROUP_WHOLE_MATCH);
    }

    public function isGroupMatched($nameOrIndex, int $index): bool
    {
        $var = $this->matches[$nameOrIndex][$index];
        if (\is_array($var)) {
            return $var[1] !== -1;
        }
        return false;
    }

    public function getRawMatchOffset(int $index): RawMatchOffset
    {
        $matches = \array_map(static function (array $match) use ($index) {
            return $match[$index];
        }, $this->matches);
        return new RawMatchOffset($matches, $index);
    }

    public function getRawMatch(int $index): RawMatch
    {
        return new RawMatch(\array_map(static function (array $match) use ($index) {
            [$text, $offset] = $match[$index];
            return $text;
        }, $this->matches));
    }

    public function filterMatchesByDetailObjects(Predicate $predicate, DetailObjectFactory $factory): array
    {
        $matchObjects = $this->getDetailObjects($factory);
        $filteredMatches = \array_filter($matchObjects, [$predicate, 'test']);

        return \array_map(static function (array $match) use ($filteredMatches) {
            return \array_intersect_key($match, $filteredMatches);
        }, $this->matches);
    }

    /**
     * @return int[]
     */
    public function getIndexes(): array
    {
        return \array_keys($this->matches[0]);
    }
}
