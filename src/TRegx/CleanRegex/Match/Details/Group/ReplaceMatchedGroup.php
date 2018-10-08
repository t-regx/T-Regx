<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Factory\Group\MatchedGroupDetails;

class ReplaceMatchedGroup extends MatchedGroup implements ReplaceMatchGroup
{
    /** @var int */
    private $offsetModification;

    public function __construct(GroupDetails $details, MatchedGroupDetails $matchedDetails, int $offsetModification)
    {
        parent::__construct($details, $matchedDetails);
        $this->offsetModification = $offsetModification;
    }

    public function modifiedOffset(): int
    {
        return $this->offset() + $this->offsetModification;
    }
}
