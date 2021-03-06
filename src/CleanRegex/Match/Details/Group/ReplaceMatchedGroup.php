<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroupOccurrence;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;

class ReplaceMatchedGroup extends MatchedGroup implements ReplaceGroup
{
    /** @var int */
    private $byteOffsetModification;
    /** @var string */
    private $subjectModification;

    public function __construct(IRawMatchOffset $match,
                                GroupDetails $details,
                                MatchedGroupOccurrence $matchedDetails,
                                int $byteOffsetModification,
                                string $subjectModification)
    {
        parent::__construct($match, $details, $matchedDetails);
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
