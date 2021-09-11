<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Messages\Group\FirstGroupMessage;
use TRegx\CleanRegex\Internal\Messages\Group\MethodGetGroupMessage;
use TRegx\CleanRegex\Internal\Messages\Group\MethodGroupMessage;
use TRegx\CleanRegex\Internal\Messages\Group\NthGroupMessage;
use TRegx\CleanRegex\Internal\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\FirstGroupOffsetMessage;

class GroupNotMatchedException extends \Exception implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    private static function exception(NotMatchedMessage $message): self
    {
        return new GroupNotMatchedException($message->getMessage());
    }

    public static function forFirst(GroupKey $group): self
    {
        return self::exception(new FirstGroupMessage($group));
    }

    public static function forFirstOffset(GroupKey $group): self
    {
        return self::exception(new FirstGroupOffsetMessage($group));
    }

    public static function forNth(GroupKey $group, int $index): self
    {
        return self::exception(new NthGroupMessage($group, $index));
    }

    public static function forMethod(GroupKey $group, string $method): self
    {
        return self::exception(new MethodGroupMessage($method, $group));
    }

    public static function forReplacement(GroupKey $group): self
    {
        return self::exception(new ReplacementWithUnmatchedGroupMessage($group));
    }

    public static function forGet(GroupKey $group): self
    {
        return self::exception(new MethodGetGroupMessage($group));
    }
}
