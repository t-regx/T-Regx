<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

class MatchGroupFactoryStrategy implements GroupFactoryStrategy
{
    public function createMatched(Subjectable $subjectable,
                                  GroupDetails $details,
                                  MatchedGroupOccurrence $matchedDetails,
                                  SubstitutedGroup $substitutedGroup): MatchedGroup
    {
        return new MatchedGroup($subjectable, $details, $matchedDetails, $substitutedGroup);
    }

    public function createUnmatched(GroupDetails $details,
                                    GroupExceptionFactory $exceptionFactory,
                                    NotMatchedOptionalWorker $optionalFactory,
                                    string $subject): NotMatchedGroup
    {
        return new NotMatchedGroup($details, $exceptionFactory, $optionalFactory, $subject);
    }
}
