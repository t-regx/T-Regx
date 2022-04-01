<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

/**
 * @deprecated
 */
class RawMatchesOffset implements GroupAware
{
    /** @var array */
    public $matches;

    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    public function matched(): bool
    {
        return \count($this->matches[0]) > 0;
    }

    public function getCount(): int
    {
        return \count($this->matches[0]);
    }

    public function hasGroup(GroupKey $group): bool
    {
        return \array_key_exists($group->nameOrIndex(), $this->matches);
    }

    public function getLimitedGroupOffsets($nameOrIndex, int $limit): array
    {
        return $this->mapToOffset($this->getLimitedGroups($nameOrIndex, $limit));
    }

    private function getLimitedGroups($group, int $limit): array
    {
        $match = $this->matches[$group];
        if ($limit === -1) {
            return $match;
        }
        return \array_slice($match, 0, $limit);
    }

    private function mapToOffset(array $matches): array
    {
        return \array_map([$this, 'mapMatch'], $matches);
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
        [$text, $offset] = $this->matches[0][$index];
        return $offset;
    }

    public function getTextAndOffset(int $index): array
    {
        return $this->matches[0][$index];
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

    public function getGroupsOffsets(int $index): array
    {
        return \array_map(static function (array $match) use ($index) {
            [$text, $offset] = $match[$index];
            return $offset;
        }, $this->matches);
    }

    public function getGroupsTexts(int $index): array
    {
        return \array_map(static function (array $match) use ($index): ?string {
            [$text, $offset] = $match[$index];
            if ($offset === -1) {
                return null;
            }
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
        return $this->getGroupTexts(0);
    }

    public function isGroupMatched($nameOrIndex, int $index): bool
    {
        $var = $this->matches[$nameOrIndex][$index];
        if (\is_array($var)) {
            return $var[1] !== -1;
        }
        return false;
    }

    /**
     * @return int[]
     */
    public function getIndexes(): array
    {
        return \array_keys($this->matches[0]);
    }
}
