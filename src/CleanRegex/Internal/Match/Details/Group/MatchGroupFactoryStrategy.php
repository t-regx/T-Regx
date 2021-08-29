<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

class MatchGroupFactoryStrategy implements GroupFactoryStrategy
{
    public function createMatched(Subject          $subject,
                                  GroupDetails     $details,
                                  GroupEntry       $groupEntry,
                                  SubstitutedGroup $substitutedGroup): MatchedGroup
    {
        return new MatchedGroup($subject, $details, $groupEntry, $substitutedGroup);
    }

    public function createUnmatched(GroupDetails             $details,
                                    GroupExceptionFactory    $exceptionFactory,
                                    NotMatchedOptionalWorker $optionalFactory,
                                    Subject                  $subject): NotMatchedGroup
    {
        return new NotMatchedGroup($details, $exceptionFactory, $optionalFactory, $subject);
    }
}
