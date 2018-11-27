<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroupOccurrence;

class ReplaceMatchedGroup extends MatchedGroup implements ReplaceMatchGroup
{
    /** @var int */
    private $offsetModification;

    public function __construct(GroupDetails $details, MatchedGroupOccurrence $matchedDetails, int $offsetModification)
    {
        parent::__construct($details, $matchedDetails);
        $this->offsetModification = $offsetModification;
    }

    public function modifiedOffset(): int
    {
        return $this->offset() + $this->offsetModification;
    }
}
