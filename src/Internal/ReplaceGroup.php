<?php
namespace Regex\Internal;

use Regex\GroupException;

class ReplaceGroup implements Replacer
{
    private GroupKey $groupKey;

    public function __construct(GroupKeys $groupKeys, GroupKey $group)
    {
        if (!$groupKeys->groupExists($group)) {
            throw new GroupException($group, 'does not exist');
        }
        $this->groupKey = $group;
    }

    public function replace(array $match): string
    {
        if (\array_key_exists($this->groupKey->nameOrIndex, $match)) {
            [$text, $offset] = $match[$this->groupKey->nameOrIndex];
            if ($offset !== -1) {
                return $text;
            }
        }
        throw new GroupException($this->groupKey, 'is not matched');
    }
}
