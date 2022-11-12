<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class GroupNotMatchedException extends \RuntimeException implements PatternException
{
    public static function forReplacement(GroupKey $group): self
    {
        return new self("Expected to replace with group $group, but the group was not matched");
    }

    public static function forGroupBy(GroupKey $group): self
    {
        return new self("Expected to group matches by group $group, but the group was not matched");
    }
}
