<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class GroupNames
{
    /** @var GroupAware */
    private $groupAware;

    public function __construct(GroupAware $groupAware)
    {
        $this->groupAware = $groupAware;
    }

    public function groupNames(): array
    {
        $groupKeys = $this->groupAware->getGroupKeys();
        if (\count($groupKeys) <= 1) {
            return [];
        }
        return $this->filterOutUnnamedGroups(\array_slice($groupKeys, 1));
    }

    private function filterOutUnnamedGroups(array $groups): array
    {
        $result = [];
        $lastWasString = false;
        foreach ($groups as $group) {
            if (\is_string($group)) {
                $result[] = $group;
                $lastWasString = true;
            } else if (\is_int($group)) {
                if ($lastWasString) {
                    $lastWasString = false;
                } else {
                    $result[] = null;
                }
            } else {
                // @codeCoverageIgnoreStart
                throw new InternalCleanRegexException();
                // @codeCoverageIgnoreEnd
            }
        }
        return $result;
    }
}
