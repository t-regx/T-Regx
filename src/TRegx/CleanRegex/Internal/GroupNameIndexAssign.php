<?php
namespace TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Exception\InsufficientMatchException;
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
        try {
            return $this->tryGetNameAndIndex($group);
        } catch (InsufficientMatchException $exception) {
            return $this->reloadAndTryGetNameAndIndex($group);
        }
    }

    private function reloadAndTryGetNameAndIndex($group): array
    {
        $this->groupKeys = $this->allGroupKeysFactory->getRawMatches()->getGroupKeys();
        try {
            return $this->tryGetNameAndIndex($group);
        } catch (InsufficientMatchException $exception) {
            throw new InvalidArgumentException($group);
        }
    }

    private function tryGetNameAndIndex($group): array
    {
        if (is_string($group)) {
            return [$group, $this->getIndexByName($group)];
        }
        if (is_int($group)) {
            return [$this->getNameByIndex($group), $group];
        }
        throw new InvalidArgumentException();
    }

    private function getIndexByName(string $name): int
    {
        $key = $this->getKeyByGroup($name);
        return $this->groupKeys[$key + 1];
    }

    private function getNameByIndex(int $index): ?string
    {
        $key = $this->getKeyByGroup($index);
        if ($key === 0) {
            return null;
        }
        $value = $this->groupKeys[$key - 1];
        if (is_string($value)) {
            return $value;
        }
        return null;
    }

    private function getKeyByGroup($group)
    {
        $key = array_search($group, $this->groupKeys, true);
        if ($key === false) {
            throw new InsufficientMatchException();
        }
        return $key;
    }
}
