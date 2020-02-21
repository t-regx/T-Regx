<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

interface Strategy
{
    function transform(array $groups, IRawMatchesOffset $matches): array;
}
