<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchTripleMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchTupleMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromNthMatchMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\NthMatchMessage;
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
        return self::withMessage(new FromNthMatchMessage($group, $index), $subject);
    }

    public static function forFirstGroup(Subject $subject, GroupKey $group): self
    {
        return self::withMessage(new FromFirstMatchMessage($group), $subject);
    }

    public static function forFirstTuple(Subject $subject, GroupKey $group1, GroupKey $group2): self
    {
        throw SubjectNotMatchedException::withMessage(new FromFirstMatchTupleMessage($group1, $group2), $subject);
    }

    public static function forFirstTriple(Subject $subject, GroupKey $group1, GroupKey $group2, GroupKey $group3): self
    {
        throw SubjectNotMatchedException::withMessage(new FromFirstMatchTripleMessage($group1, $group2, $group3), $subject);
    }

    private static function withMessage(NotMatchedMessage $message, Subject $subject): self
    {
        return new SubjectNotMatchedException($message->getMessage(), $subject);
    }
}
