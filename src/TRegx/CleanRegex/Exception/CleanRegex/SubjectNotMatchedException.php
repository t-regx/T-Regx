<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\Subject\FirstGroupSubjectMessage;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Subjectable;

class SubjectNotMatchedException extends CleanRegexException
{
    /** @var string */
    private $subject; // Debugger

    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
        $this->subject = $subject;
    }

    public static function forFirst(Subjectable $subjectable): SubjectNotMatchedException
    {
        return new SubjectNotMatchedException((new FirstMatchMessage())->getMessage(), $subjectable->getSubject());
    }

    public static function forFirstGroup(Subjectable $subjectable, $group): SubjectNotMatchedException
    {
        return new SubjectNotMatchedException((new FirstGroupSubjectMessage($group))->getMessage(), $subjectable->getSubject());
    }
}
