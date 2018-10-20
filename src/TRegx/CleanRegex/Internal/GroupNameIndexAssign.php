<?php
namespace TRegx\CleanRegex\Internal;

use function array_keys;
use function array_search;
use function is_int;
use function is_string;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\RawWithGroups;

class GroupNameIndexAssign
{
    /** @var (string|int)[] */
    private $groups;

    public function __construct(RawWithGroups $matches)
    {
        $this->groups = $matches->getGroupKeys();
    }

    public function getNameAndIndex($group): array
    {
        if (is_string($group)) {
            return $this->getByName($group);
        }
        if (is_int($group)) {
            return $this->getByIndex($group);
        }
        return [];
    }

    private function getByName(string $group): array
    {
        return [$group, $this->getIndexByName($group)];
    }

    private function getByIndex(int $group): array
    {
        return [$this->getNameByIndex($group), $group];
    }

    private function getIndexByName(string $name): int
    {
        $key = array_search($name, $this->groups, true);
        return $this->groups[$key + 1];
    }

    private function getNameByIndex(int $index): ?string
    {
        $key = array_search($index, $this->groups, true);
        if ($key === 0) {
            return null;
        }
        $value = $this->groups[$key - 1];
        if (is_string($value)) {
            return $value;
        }
        return null;
    }
}
