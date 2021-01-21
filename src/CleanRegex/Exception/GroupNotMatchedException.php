<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\Group\FirstGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\MethodGetGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\MethodGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\NthGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Subjectable;

class GroupNotMatchedException extends \Exception implements PatternException
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

    private static function exception(NotMatchedMessage $message, Subjectable $subject, $group): self
    {
        return new GroupNotMatchedException($message->getMessage(), $subject->getSubject(), $group);
    }

    public static function forFirst(Subjectable $subject, $group): self
    {
        return self::exception(new FirstGroupMessage($group), $subject, $group);
    }

    public static function forNth(Subjectable $subject, $group, int $index): self
    {
        return self::exception(new NthGroupMessage($group, $index), $subject, $group);
    }

    public static function forMethod(Subjectable $subject, $group, string $method): self
    {
        return self::exception(new MethodGroupMessage($method, $group), $subject, $group);
    }

    public static function forReplacement(Subjectable $subject, $group): self
    {
        return self::exception(new ReplacementWithUnmatchedGroupMessage($group), $subject, $group);
    }

    public static function forGet(Subjectable $subject, $group): self
    {
        return self::exception(new MethodGetGroupMessage($group), $subject, $group);
    }
}
