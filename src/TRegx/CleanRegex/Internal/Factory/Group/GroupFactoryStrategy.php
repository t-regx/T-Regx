<?php
namespace TRegx\CleanRegex\Internal\Factory\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

interface GroupFactoryStrategy
{
    function createMatched(GroupDetails $details,
                           MatchedGroupOccurrence $matchedDetails): MatchedGroup;

    function createUnmatched(GroupDetails $details,
                             GroupExceptionFactory $exceptionFactory,
                             NotMatchedOptionalWorker $optionalFactory): NotMatchedGroup;
}
