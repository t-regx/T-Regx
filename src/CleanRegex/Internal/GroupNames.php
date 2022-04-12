<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\GroupKeys;

class GroupNames
{
    /** @var GroupKeys */
    private $groupKeys;

    public function __construct(GroupKeys $groupKeys)
    {
        $this->groupKeys = $groupKeys;
    }

    public function groupNames(): array
    {
        return $this->withoutUnnamedGroups(\array_slice($this->groupKeys->getGroupKeys(), 1));
    }

    private function withoutUnnamedGroups(array $groupKeys): array
    {
        $names = [];
        $lastWasString = false;
        foreach ($groupKeys as $groupKey) {
            if (\is_string($groupKey)) {
                $names[] = $groupKey;
                $lastWasString = true;
            } else if (\is_int($groupKey)) {
                if ($lastWasString) {
                    $lastWasString = false;
                } else {
                    $names[] = null;
                }
            } else {
                // @codeCoverageIgnoreStart
                throw new InternalCleanRegexException();
                // @codeCoverageIgnoreEnd
            }
        }
        return $names;
    }
}
