<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Replace\Details\EntryModification;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Replace\Details\Group\ReplaceMatchedGroup;
use TRegx\CleanRegex\Replace\Details\Group\ReplaceNotMatchedGroup;

class ReplaceMatchGroupFactoryStrategy implements GroupFactoryStrategy
{
    /** @var int */
    private $byteOffsetModification;
    /** @var string */
    private $subjectModification;

    public function __construct(int $byteOffsetModification, string $subjectModification)
    {
        $this->byteOffsetModification = $byteOffsetModification;
        $this->subjectModification = $subjectModification;
    }

    public function matched(Subject $subject, GroupDetails $details, GroupEntry $entry, SubstitutedGroup $substituted): MatchedGroup
    {
        return new ReplaceMatchedGroup($subject, $details, $entry, $substituted,
            new EntryModification($entry, $this->subjectModification, $this->byteOffsetModification));
    }

    public function notMatched(Subject $subject, GroupDetails $details): NotMatchedGroup
    {
        return new ReplaceNotMatchedGroup($subject, $details);
    }
}
