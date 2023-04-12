<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

/**
 * @deprecated
 */
class RawMatchesOffset implements GroupAware
{
    /** @var array<int|string, list<array{string, int}>> */
    public $matches;

    /**
     * @param array<int|string, list<array{string, int}>> $matches
     */
    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    public function matched(): bool
    {
        return \count($this->matches[0]) > 0;
    }

    public function hasGroup(GroupKey $group): bool
    {
        return \array_key_exists($group->nameOrIndex(), $this->matches);
    }

    public function getOffset(int $index): int
    {
        [$text, $offset] = $this->matches[0][$index];
        return $offset;
    }

    /**
     * @param int|string $nameOrIndex
     * @param int $index
     * @return array{string, int}
     */
    public function getGroupTextAndOffset($nameOrIndex, int $index): array
    {
        return $this->matches[$nameOrIndex][$index];
    }

    /**
     * @return list<int|string>
     */
    public function getGroupKeys(): array
    {
        return \array_keys($this->matches);
    }

    /**
     * @param int|string $group
     * @return (string|null)[]
     */
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

    /**
     * @return (string|null)[]
     */
    public function getTexts(): array
    {
        return $this->getGroupTexts(0);
    }

    /**
     * @param int|string $nameOrIndex
     */
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
