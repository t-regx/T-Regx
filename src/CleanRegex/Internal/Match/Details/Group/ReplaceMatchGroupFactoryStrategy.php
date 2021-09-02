<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
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
        return new ReplaceMatchedGroup($subject, $details, $entry, $substituted, $this->byteOffsetModification, $this->subjectModification);
    }

    public function notMatched(Subject $subject, GroupDetails $details, NotMatchedOptionalWorker $worker): NotMatchedGroup
    {
        return new ReplaceNotMatchedGroup($subject, $details, $worker);
    }
}
