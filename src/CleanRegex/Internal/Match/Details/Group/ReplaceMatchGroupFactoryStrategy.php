<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Subjectable;
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

    public function createMatched(Subjectable $subjectable,
                                  GroupDetails $details,
                                  GroupEntry $groupEntry,
                                  SubstitutedGroup $substitutedGroup): MatchedGroup
    {
        return new ReplaceMatchedGroup($subjectable, $details, $groupEntry, $substitutedGroup, $this->byteOffsetModification, $this->subjectModification);
    }

    public function createUnmatched(GroupDetails $details,
                                    GroupExceptionFactory $exceptionFactory,
                                    NotMatchedOptionalWorker $optionalFactory,
                                    string $subject): NotMatchedGroup
    {
        return new ReplaceNotMatchedGroup($details, $exceptionFactory, $optionalFactory, $subject);
    }
}
