<?php
namespace CleanRegex\Exception\CleanRegex;

class SubjectNotMatchedException extends CleanRegexException
{
    public const MESSAGE = 'Expected to get first match in the subject, but subject was not matched at all';

    /** @var string */
    private $subject; // Debugger

    public function __construct(string $message, string $subject)
    {
        $this->message = $message;
        $this->subject = $subject;
    }

    public static function forFirst(string $subject): SubjectNotMatchedException
    {
        return new SubjectNotMatchedException(self::MESSAGE, $subject);
    }
}
