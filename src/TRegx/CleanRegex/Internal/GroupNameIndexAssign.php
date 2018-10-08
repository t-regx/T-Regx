<?php
namespace TRegx\CleanRegex\Internal;

use function array_keys;
use function array_search;
use function is_int;
use function is_string;

class GroupNameIndexAssign
{
    /** @var array */
    private $matches;

    public function __construct(array $matches)
    {
        $this->matches = array_keys($matches);
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
        $key = array_search($name, $this->matches, true);
        return $this->matches[$key + 1];
    }

    private function getNameByIndex(int $index): ?string
    {
        $key = array_search($index, $this->matches, true);
        if ($key === 0) {
            return null;
        }
        $value = $this->matches[$key - 1];
        if (is_string($value)) {
            return $value;
        }
        return null;
    }
}
