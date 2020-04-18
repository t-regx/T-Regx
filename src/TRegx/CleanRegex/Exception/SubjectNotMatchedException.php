<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
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
        return self::withMessage(new FirstMatchMessage(), $subjectable);
    }

    public static function forFirstOffset(Subjectable $subjectable): SubjectNotMatchedException
    {
        return self::withMessage(new FirstMatchOffsetMessage(), $subjectable);
    }

    public static function forFirstGroupOffset(Subjectable $subjectable, $group): SubjectNotMatchedException
    {
        return self::withMessage(new FirstGroupOffsetMessage($group), $subjectable);
    }

    public static function forFirstGroup(Subjectable $subjectable, $group): SubjectNotMatchedException
    {
        return self::withMessage(new FirstGroupSubjectMessage($group), $subjectable);
    }

    public static function withMessage(NotMatchedMessage $message, Subjectable $subjectable): SubjectNotMatchedException
    {
        return new SubjectNotMatchedException($message->getMessage(), $subjectable->getSubject());
    }
}
