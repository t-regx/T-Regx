<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\GroupNotMatched\FromFirstMatchMessage;
use TRegx\CleanRegex\Internal\Message\GroupNotMatched\FromNthMatchMessage;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Message\Replace\WithUnmatchedGroupMessage;

class GroupNotMatchedException extends \Exception implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    private static function exception(Message $message): self
    {
        return new GroupNotMatchedException($message->getMessage());
    }

    public static function forFirst(GroupKey $group): self
    {
        return self::exception(new FromFirstMatchMessage($group));
    }

    public static function forNth(GroupKey $group, int $index): self
    {
        return self::exception(new FromNthMatchMessage($group, $index));
    }

    public static function forMethod(GroupKey $group, string $method): self
    {
        return new self("Expected to call $method() for group $group, but the group was not matched");
    }

    public static function forReplacement(GroupKey $group): self
    {
        return self::exception(new WithUnmatchedGroupMessage($group));
    }

    public static function forGet(GroupKey $group): self
    {
        return new self("Expected to get group $group, but the group was not matched");
    }
}
