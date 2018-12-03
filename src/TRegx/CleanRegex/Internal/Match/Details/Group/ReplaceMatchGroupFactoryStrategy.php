<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\ReplaceNotMatchedGroup;

class ReplaceMatchGroupFactoryStrategy implements GroupFactoryStrategy
{
    /** @var int */
    private $offsetModification;

    public function __construct(int $offsetModification)
    {
        $this->offsetModification = $offsetModification;
    }

    public function createMatched(GroupDetails $details,
                                  MatchedGroupOccurrence $matchedDetails): MatchedGroup
    {
        return new ReplaceMatchedGroup($details, $matchedDetails, $this->offsetModification);
    }

    public function createUnmatched(GroupDetails $details,
                                    GroupExceptionFactory $exceptionFactory,
                                    NotMatchedOptionalWorker $optionalFactory): NotMatchedGroup
    {
        return new ReplaceNotMatchedGroup($details, $exceptionFactory, $optionalFactory);
    }
}
