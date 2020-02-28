<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstGroupOffsetMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstGroupSubjectMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Subjectable;

class SubjectNotMatchedException extends PatternException
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

    public static function forFirstOffset(Subjectable $subjectable): SubjectNotMatchedException
    {
        return new SubjectNotMatchedException((new FirstMatchOffsetMessage())->getMessage(), $subjectable->getSubject());
    }

    public static function forFirstGroupOffset(Subjectable $subjectable, $group): SubjectNotMatchedException
    {
        return new SubjectNotMatchedException((new FirstGroupOffsetMessage($group))->getMessage(), $subjectable->getSubject());
    }

    public static function forFirstGroup(Subjectable $subjectable, $group): SubjectNotMatchedException
    {
        return new SubjectNotMatchedException((new FirstGroupSubjectMessage($group))->getMessage(), $subjectable->getSubject());
    }
}
