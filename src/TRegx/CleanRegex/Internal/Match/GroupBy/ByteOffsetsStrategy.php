<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class ByteOffsetsStrategy implements Strategy
{
    function transform(array $groups, IRawMatchesOffset $matches): array
    {
        foreach ($groups as &$group) {
            $group = array_map(function (IRawMatchOffset $match) {
                return $match->byteOffset();
            }, $group);
        }
        return $groups;
    }
}
