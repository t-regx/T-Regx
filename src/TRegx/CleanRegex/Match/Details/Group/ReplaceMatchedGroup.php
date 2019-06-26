<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroupOccurrence;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;

class ReplaceMatchedGroup extends MatchedGroup implements ReplaceMatchGroup
{
    /** @var int */
    private $offsetModification;

    public function __construct(IRawMatchOffset $match, GroupDetails $details, MatchedGroupOccurrence $matchedDetails, int $offsetModification)
    {
        parent::__construct($match, $details, $matchedDetails);
        $this->offsetModification = $offsetModification;
    }

    public function modifiedOffset(): int
    {
        return $this->offset() + $this->offsetModification;
    }
}
