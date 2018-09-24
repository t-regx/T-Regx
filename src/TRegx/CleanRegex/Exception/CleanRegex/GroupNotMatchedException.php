<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

class GroupNotMatchedException extends CleanRegexException
{
    public const FOR_FIRST_MESSAGE = 'Expected to get first group in the subject, but group was not matched at all';
    public const FOR_METHOD_MESSAGE = "Expected to call %s() for group '%s', but group was not matched at all";

    /** @var string */
    private $subject; // Debugger

    /** @var string|int */
    private $group;   // Debugger

    public function __construct(string $message, string $subject, $group)
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->group = $group;
    }

    public static function forFirst(string $subject, $group): GroupNotMatchedException
    {
        return new GroupNotMatchedException(self::FOR_FIRST_MESSAGE, $subject, $group);
    }

    public static function forMethod(string $subject, $group, string $method): GroupNotMatchedException
    {
        $message = sprintf(self::FOR_METHOD_MESSAGE, $method, $group);
        return new GroupNotMatchedException($message, $subject, $group);
    }
}
