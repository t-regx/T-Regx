<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
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

    public function createMatched(IRawMatchOffset $match, GroupDetails $details, MatchedGroupOccurrence $matchedDetails): MatchedGroup
    {
        return new ReplaceMatchedGroup($match, $details, $matchedDetails, $this->offsetModification);
    }

    public function createUnmatched(GroupDetails $details,
                                    GroupExceptionFactory $exceptionFactory,
                                    NotMatchedOptionalWorker $optionalFactory): NotMatchedGroup
    {
        return new ReplaceNotMatchedGroup($details, $exceptionFactory, $optionalFactory);
    }
}
