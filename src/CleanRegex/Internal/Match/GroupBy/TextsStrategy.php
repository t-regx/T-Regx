<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Model\Match\IRawMatch;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class TextsStrategy implements Strategy
{
    public function transform(array $groups, RawMatchesOffset $matches): array
    {
        foreach ($groups as &$group) {
            $group = \array_map(static function (IRawMatch $match) {
                return $match->getText();
            }, $group);
        }
        return $groups;
    }
}
