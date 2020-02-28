<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;

class GroupNames
{
    /** @var IRawWithGroups */
    private $match;

    public function __construct(IRawWithGroups $match)
    {
        $this->match = $match;
    }

    /**
     * @return (string|null)[]
     */
    public function groupNames(): array
    {
        $groupKeys = $this->match->getGroupKeys();
        if (\count($groupKeys) <= 1) {
            return [];
        }
        return $this->filterOutUnnamedGroups(\array_slice($groupKeys, 1));
    }

    private function filterOutUnnamedGroups(array $groups): array
    {
        $result = [];
        $lastWasString = false;
        foreach ($groups as $nameOrIndex) {
            if (\is_string($nameOrIndex)) {
                $result[] = $nameOrIndex;
                $lastWasString = true;
            } else if (\is_int($nameOrIndex)) {
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
