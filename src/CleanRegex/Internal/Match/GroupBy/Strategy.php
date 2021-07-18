<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

interface Strategy
{
    public function transform(array $groups, RawMatchesOffset $matches): array;
}
