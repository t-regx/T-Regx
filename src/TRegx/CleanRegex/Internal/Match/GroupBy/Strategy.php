<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

interface Strategy
{
    function transform(array $groups, RawMatchesOffset $matches): array;
}
