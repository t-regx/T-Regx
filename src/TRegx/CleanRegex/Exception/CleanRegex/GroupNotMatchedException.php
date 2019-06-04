<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\Group\FirstGroupMessage;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\Group\MethodGroupMessage;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Subjectable;

class GroupNotMatchedException extends CleanRegexException
{
    /** @var string */
    private $subject; // Debugger

    /** @var string|int */
    private $group;   // Debugger

    public function __construct(string $message, Subjectable $subjectable, $group)
    {
        parent::__construct($message);
        $this->subject = $subjectable->getSubject();
        $this->group = $group;
    }

    public static function forFirst(Subjectable $subject, $group): GroupNotMatchedException
    {
        $message = new FirstGroupMessage($group);
        return new GroupNotMatchedException($message->getMessage(), $subject, $group);
    }

    public static function forMethod(Subjectable $subject, $group, string $method): GroupNotMatchedException
    {
        $message = new MethodGroupMessage($method, $group);
        return new GroupNotMatchedException($message->getMessage(), $subject, $group);
    }

    public static function forReplacement(Subjectable $subject, $group): GroupNotMatchedException
    {
        $message = new ReplacementWithUnmatchedGroupMessage($group);
        return new GroupNotMatchedException($message->getMessage(), $subject, $group);
    }
}
