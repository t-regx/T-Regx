<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Exception\InsufficientMatchException;

class Signatures
{
    /** @var array */
    private $groupKeys;

    public function __construct(array $groupKeys)
    {
        $this->groupKeys = $groupKeys;
    }

    public function signature($nameOrIndex): GroupSignature
    {
        if (\is_string($nameOrIndex)) {
            return new GroupSignature($this->getIndexByName($nameOrIndex), $nameOrIndex);
        }
        if (\is_int($nameOrIndex)) {
            return new GroupSignature($nameOrIndex, $this->getNameByIndex($nameOrIndex));
        }
        throw new InternalCleanRegexException();
    }

    private function getIndexByName(string $name): int
    {
        $key = $this->getKeyByGroup($name);

        // We're relying on the assumption, that the string-key, representing the name
        // of the group, is always one index before the integer-key representing the group.
        if (\array_key_exists($key + 1, $this->groupKeys)) {
            return $this->groupKeys[$key + 1];
        }
        throw new InternalCleanRegexException();
    }

    private function getNameByIndex(int $index): ?string
    {
        $key = $this->getKeyByGroup($index);
        if ($key === 0) {
            return null;
        }
        $groupName = $this->groupKeys[$key - 1];
        if (\is_string($groupName)) {
            return $groupName;
        }
        return null;
    }

    private function getKeyByGroup($group)
    {
        $key = \array_search($group, $this->groupKeys, true);
        if ($key === false) {
            throw new InsufficientMatchException();
        }
        return $key;
    }
}
