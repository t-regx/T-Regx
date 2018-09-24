<?php
namespace TRegx\CleanRegex\Internal;

use function array_search;

class GroupNameIndexAssign
{
    /** @var array */
    private $matches;
    /** @var string|int */
    private $group;

    public function __construct(array $matches, $nameOrIndex)
    {
        $this->matches = array_keys($matches);
        $this->group = $nameOrIndex;
    }

    public function getNameAndIndex(): array
    {
        if (is_string($this->group)) {
            return $this->getByName();
        }
        if (is_int($this->group)) {
            return $this->getByIndex();
        }
        return [];
    }

    private function getByName(): array
    {
        return [$this->group, $this->getIndexByName($this->group)];
    }

    private function getByIndex(): array
    {
        return [$this->getNameByIndex($this->group), $this->group];
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
