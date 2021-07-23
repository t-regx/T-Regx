<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

interface GroupFactoryStrategy
{
    public function createMatched(MatchEntry $match,
                                  GroupDetails $details,
                                  MatchedGroupOccurrence $matchedDetails): MatchedGroup;

    public function createUnmatched(GroupDetails $details,
                                    GroupExceptionFactory $exceptionFactory,
                                    NotMatchedOptionalWorker $optionalFactory,
                                    string $subject): NotMatchedGroup;
}
