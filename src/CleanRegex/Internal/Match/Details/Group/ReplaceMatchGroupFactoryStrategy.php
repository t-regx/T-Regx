<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\ReplaceNotMatchedGroup;

class ReplaceMatchGroupFactoryStrategy implements GroupFactoryStrategy
{
    /** @var int */
    private $byteOffsetModification;
    /** @var string */
    private $subjectModification;

    public function __construct(int $byteOffsetModification, string $subjectModification)
    {
        $this->byteOffsetModification = $byteOffsetModification;
        $this->subjectModification = $subjectModification;
    }

    public function createMatched(Subject          $subject,
                                  GroupDetails     $details,
                                  GroupEntry       $groupEntry,
                                  SubstitutedGroup $substitutedGroup): MatchedGroup
    {
        return new ReplaceMatchedGroup($subject, $details, $groupEntry, $substitutedGroup, $this->byteOffsetModification, $this->subjectModification);
    }

    public function createUnmatched(GroupDetails             $details,
                                    GroupExceptionFactory    $exceptionFactory,
                                    NotMatchedOptionalWorker $optionalFactory,
                                    Subject                  $subject): NotMatchedGroup
    {
        return new ReplaceNotMatchedGroup($details, $exceptionFactory, $optionalFactory, $subject);
    }
}
