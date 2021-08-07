<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\Group\FirstGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\MethodGetGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\MethodGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\NthGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstGroupOffsetMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Subjectable;

class GroupNotMatchedException extends \Exception implements PatternException
{
    /** @var string */
    private $subject; // Debugger
    /** @var string|int|null */
    private $group;   // Debugger

    public function __construct(string $message, string $subject, GroupKey $groupId = null)
    {
        parent::__construct($message);
        $this->subject = $subject;
        $this->group = $groupId ? $groupId->nameOrIndex() : null;
    }

    private static function exception(NotMatchedMessage $message, Subjectable $subject, GroupKey $group): self
    {
        return new GroupNotMatchedException($message->getMessage(), $subject->getSubject(), $group);
    }

    public static function forFirst(Subjectable $subject, GroupKey $group): self
    {
        return self::exception(new FirstGroupMessage($group), $subject, $group);
    }

    public static function forFirstOffset(Subjectable $subject, GroupKey $group): self
    {
        return self::exception(new FirstGroupOffsetMessage($group), $subject, $group);
    }

    public static function forNth(Subjectable $subject, GroupKey $group, int $index): self
    {
        return self::exception(new NthGroupMessage($group, $index), $subject, $group);
    }

    public static function forMethod(Subjectable $subject, GroupKey $group, string $method): self
    {
        return self::exception(new MethodGroupMessage($method, $group), $subject, $group);
    }

    public static function forReplacement(Subjectable $subject, GroupKey $group): self
    {
        return self::exception(new ReplacementWithUnmatchedGroupMessage($group), $subject, $group);
    }

    public static function forGet(Subjectable $subject, GroupKey $group): self
    {
        return self::exception(new MethodGetGroupMessage($group), $subject, $group);
    }
}
