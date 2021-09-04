<?php
namespace TRegx\CleanRegex\Replace\Details\Group;

use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupEntry;
use TRegx\CleanRegex\Internal\Match\Details\Group\SubstitutedGroup;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Replace\Details\Modification;

class ReplaceMatchedGroup extends MatchedGroup implements ReplaceGroup
{
    /** @var Modification */
    private $modification;

    public function __construct(Subject $subject, GroupDetails $details, GroupEntry $entry, SubstitutedGroup $substituted, Modification $modification)
    {
        parent::__construct($subject, $details, $entry, $substituted);
        $this->modification = $modification;
    }

    public function modifiedSubject(): string
    {
        return $this->modification->subject();
    }

    public function modifiedOffset(): int
    {
        return $this->modification->offset();
    }

    public function byteModifiedOffset(): int
    {
        return $this->modification->byteOffset();
    }
}
