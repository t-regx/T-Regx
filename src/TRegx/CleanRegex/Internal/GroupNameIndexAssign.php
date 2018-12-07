<?php
namespace TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use function array_search;
use function is_int;
use function is_string;

class GroupNameIndexAssign
{
    /** @var (string|int)[] */
    private $groupKeys;
    /** @var MatchAllFactory */
    private $allGroupKeysFactory;

    public function __construct(IRawWithGroups $matches, MatchAllFactory $allGroupKeysFactory)
    {
        $this->groupKeys = $matches->getGroupKeys();
        $this->allGroupKeysFactory = $allGroupKeysFactory;
    }

    public function getNameAndIndex($group): array
    {
        if (is_string($group)) {
            return $this->getByName($group);
        }
        if (is_int($group)) {
            return $this->getByIndex($group);
        }
        throw new InvalidArgumentException();
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
        $key = array_search($name, $this->groupKeys, true);
        if (array_key_exists($key + 1, $this->groupKeys)) {
            return $this->groupKeys[$key + 1];
        }
        $groupKeys = $this->allGroupKeysFactory->getRawMatches()->getGroupKeys();
        return array_search($name, $groupKeys, true);
    }

    private function getNameByIndex(int $index): ?string
    {
        $key = array_search($index, $this->groupKeys, true);
        if ($key === 0) {
            return null;
        }
        $value = $this->groupKeys[$key - 1];
        if (is_string($value)) {
            return $value;
        }
        return null;
    }
}
