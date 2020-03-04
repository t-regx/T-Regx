<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Model\Match\IRawMatch;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class TextsStrategy implements Strategy
{
    function transform(array $groups, IRawMatchesOffset $matches): array
    {
        foreach ($groups as &$group) {
            $group = \array_map(function (IRawMatch $match) {
                return $match->getText();
            }, $group);
        }
        return $groups;
    }
}
