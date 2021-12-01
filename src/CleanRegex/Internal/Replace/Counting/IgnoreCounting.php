<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Internal\Model\GroupAware;

class IgnoreCounting implements CountingStrategy
{
    public function count(int $replaced, GroupAware $groupAware): void
    {
    }
}
