<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;

class RawMatchAsArray
{
    public static function fromMatch(IRawMatchOffset $match, IRawWithGroups $groups): array
    {
        $groupKeys = $groups->getGroupKeys();
        return $match->getGroupsTexts() + \array_combine($groupKeys, \array_fill(0, \count($groupKeys), null));
    }
}
