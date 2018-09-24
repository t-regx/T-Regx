<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

class GroupNotMatchedException extends CleanRegexException
{
    public const MESSAGE = 'Expected to get first group in the subject, but group was not matched at all';

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
        return new GroupNotMatchedException(self::MESSAGE, $subject, $group);
    }
}
