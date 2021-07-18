<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Model\Match\IRawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

class TextsStrategy implements Strategy
{
    public function transform(array $groups, RawMatchesOffset $matches): array
    {
        $closure = static function (IRawMatch $match) {
            return $match->getText();
        };
        foreach ($groups as &$group) {
            $group = \array_map($closure, $group);
        }
        return $groups;
    }
}
