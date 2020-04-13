<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\Group\FirstGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\MethodGetGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\MethodGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Subjectable;

class GroupNotMatchedException extends PatternException
{
    /** @var string */
    private $subject; // Debugger

    /** @var string|int */
    private $group;   // Debugger

    public function __construct(string $message, string $subject, $group = null)
    {
        parent::__construct($message);
        $this->subject = $subject;
        $this->group = $group;
    }

    public static function forFirst(Subjectable $subject, $group): GroupNotMatchedException
    {
        return self::exception($subject, $group, new FirstGroupMessage($group));
    }

    public static function forMethod(Subjectable $subject, $group, string $method): GroupNotMatchedException
    {
        return self::exception($subject, $group, new MethodGroupMessage($method, $group));
    }

    public static function forReplacement(Subjectable $subject, $group): GroupNotMatchedException
    {
        return self::exception($subject, $group, new ReplacementWithUnmatchedGroupMessage($group));
    }

    public static function forGet(Subjectable $subject, $group): GroupNotMatchedException
    {
        return self::exception($subject, $group, new MethodGetGroupMessage($group));
    }

    private static function exception(Subjectable $subject, $group, NotMatchedMessage $message): GroupNotMatchedException
    {
        return new GroupNotMatchedException($message->getMessage(), $subject->getSubject(), $group);
    }
}
