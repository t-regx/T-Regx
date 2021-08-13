<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

interface GroupFactoryStrategy
{
    public function createMatched(Subjectable $subjectable,
                                  GroupDetails $details,
                                  GroupEntry $groupEntry,
                                  SubstitutedGroup $substitutedGroup): MatchedGroup;

    public function createUnmatched(GroupDetails             $details,
                                    GroupExceptionFactory    $exceptionFactory,
                                    NotMatchedOptionalWorker $optionalFactory,
                                    Subjectable              $subject): NotMatchedGroup;
}
