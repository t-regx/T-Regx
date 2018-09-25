<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Group\FirstGroupMessage;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Group\MethodGroupMessage;

class GroupNotMatchedException extends CleanRegexException
{
    /** @var string */
    private $subject; // Debugger

    /** @var string|int */
    private $group;   // Debugger

    public function __construct(string $message, string $subject, $group)
    {
        parent::__construct($message);
        $this->subject = $subject;
        $this->group = $group;
    }

    public static function forFirst(string $subject, $group): GroupNotMatchedException
    {
        $message = new FirstGroupMessage($group);
        return new GroupNotMatchedException($message->getMessage(), $subject, $group);
    }

    public static function forMethod(string $subject, $group, string $method): GroupNotMatchedException
    {
        $message = new MethodGroupMessage($method, $group);
        return new GroupNotMatchedException($message->getMessage(), $subject, $group);
    }
}
