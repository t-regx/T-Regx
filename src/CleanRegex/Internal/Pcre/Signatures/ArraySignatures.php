<?php
namespace TRegx\CleanRegex\Internal\Pcre\Signatures;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;

class ArraySignatures implements Signatures
{
    /** @var array */
    private $groupKeys;

    public function __construct(array $groupKeys)
    {
        $this->groupKeys = $groupKeys;
    }

    public function signature(GroupKey $group): GroupSignature
    {
        if (\is_string($group->nameOrIndex())) {
            return new GroupSignature($this->getIndexByName($group->nameOrIndex()), $group->nameOrIndex());
        }
        if (\is_int($group->nameOrIndex())) {
            return new GroupSignature($group->nameOrIndex(), $this->getNameByIndex($group->nameOrIndex()));
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    private function getIndexByName(string $name): int
    {
        $key = $this->getKeyByGroup($name);

        // We're relying on the assumption, that the string-key, representing the name
        // of the group, is always one index before the integer-key representing the group.
        if (\array_key_exists($key + 1, $this->groupKeys)) {
            return $this->groupKeys[$key + 1];
        }

        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
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
