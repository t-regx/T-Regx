<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class GroupNotMatchedException extends \Exception implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function forMethod(GroupKey $group, string $method): self
    {
        return new self("Expected to call $method() for group $group, but the group was not matched");
    }

    public static function forReplacement(GroupKey $group): self
    {
        return new self("Expected to replace with group $group, but the group was not matched");
    }

    public static function forGet(GroupKey $group): self
    {
        return new self("Expected to get group $group, but the group was not matched");
    }

    public static function forGroupBy(GroupKey $group): self
    {
        return new self("Expected to group matches by group $group, but the group was not matched");
    }
}
