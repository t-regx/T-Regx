<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstGroupOffsetSubjectMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstGroupSubjectMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstTripleSubjectMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstTupleSubjectMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\NthGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\NthMatchMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Subject;

class SubjectNotMatchedException extends \Exception implements PatternException
{
    /** @var string */
    private $subject; // Debugger

    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
        $this->subject = $subject;
    }

    public static function forFirst(Subject $subject): self
    {
        return self::withMessage(new FirstMatchMessage(), $subject);
    }

    public static function forNth(Subject $subject, int $index): self
    {
        return self::withMessage(new NthMatchMessage($index), $subject);
    }

    public static function forNthGroup(Subject $subject, GroupKey $group, int $index): self
    {
        return self::withMessage(new NthGroupMessage($group, $index), $subject);
    }

    public static function forFirstGroupOffset(Subject $subject, GroupKey $group): self
    {
        return self::withMessage(new FirstGroupOffsetSubjectMessage($group), $subject);
    }

    public static function forFirstGroup(Subject $subject, GroupKey $group): self
    {
        return self::withMessage(new FirstGroupSubjectMessage($group), $subject);
    }

    public static function forFirstTuple(Subject $subject, GroupKey $group1, GroupKey $group2): self
    {
        throw SubjectNotMatchedException::withMessage(new FirstTupleSubjectMessage($group1, $group2), $subject);
    }

    public static function forFirstTriple(Subject $subject, GroupKey $group1, GroupKey $group2, GroupKey $group3): self
    {
        throw SubjectNotMatchedException::withMessage(new FirstTripleSubjectMessage($group1, $group2, $group3), $subject);
    }

    public static function withMessage(NotMatchedMessage $message, Subject $subject): self
    {
        return new SubjectNotMatchedException($message->getMessage(), $subject->getSubject());
    }
}
