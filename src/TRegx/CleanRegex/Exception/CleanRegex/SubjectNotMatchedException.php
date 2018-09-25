<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Subject\FirstMatchMessage;

class SubjectNotMatchedException extends CleanRegexException
{
    /** @var string */
    private $subject; // Debugger

    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
        $this->subject = $subject;
    }

    public static function forFirst(string $subject): SubjectNotMatchedException
    {
        return new SubjectNotMatchedException((new FirstMatchMessage())->getMessage(), $subject);
    }
}
