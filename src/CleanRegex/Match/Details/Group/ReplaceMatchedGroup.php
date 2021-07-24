<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroupOccurrence;
use TRegx\CleanRegex\Internal\Match\Details\Group\SubstitutedGroup;
use TRegx\CleanRegex\Internal\Subjectable;

class ReplaceMatchedGroup extends MatchedGroup implements ReplaceGroup
{
    /** @var int */
    private $byteOffsetModification;
    /** @var string */
    private $subjectModification;

    public function __construct(Subjectable $subjectable,
                                GroupDetails $details,
                                MatchedGroupOccurrence $matchedDetails,
                                SubstitutedGroup $substitutedGroup,
                                int $byteOffsetModification,
                                string $subjectModification)
    {
        parent::__construct($subjectable, $details, $matchedDetails, $substitutedGroup);
        $this->byteOffsetModification = $byteOffsetModification;
        $this->subjectModification = $subjectModification;
    }

    public function modifiedSubject(): string
    {
        return $this->subjectModification;
    }

    public function modifiedOffset(): int
    {
        return ByteOffset::toCharacterOffset($this->subjectModification, $this->byteModifiedOffset());
    }

    public function byteModifiedOffset(): int
    {
        return $this->byteOffset() + $this->byteOffsetModification;
    }
}
