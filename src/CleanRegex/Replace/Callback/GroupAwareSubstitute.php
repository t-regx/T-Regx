<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Subject;

class GroupAwareSubstitute implements GroupSubstitute
{
    /** @var Subject */
    private $subject;
    /** @var SubjectRs */
    private $substitute;
    /** @var GroupKey */
    private $group;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(Subject $subject, SubjectRs $substitute, GroupKey $group, GroupAware $groupAware)
    {
        $this->subject = $subject;
        $this->substitute = $substitute;
        $this->group = $group;
        $this->groupAware = $groupAware;
    }

    public function substitute(string $fallback): string
    {
        if ($this->groupExists()) {
            return $this->substitute->substitute($this->subject) ?? $fallback;
        }
        throw new NonexistentGroupException($this->group);
    }

    private function groupExists(): bool
    {
        if ($this->group->nameOrIndex() === 0) {
            return true;
        }
        return $this->groupAware->hasGroup($this->group);
    }
}
