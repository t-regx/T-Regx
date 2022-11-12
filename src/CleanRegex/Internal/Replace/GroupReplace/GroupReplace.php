<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupReplace;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class GroupReplace
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $pregLimit;
    /** @var GroupAware */
    private $groupAware;
    /** @var CountingStrategy */
    private $counting;
    /** @var GroupAwake */
    private $awake;

    public function __construct(Definition $definition, Subject $subject, int $pregLimit, CountingStrategy $counting)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->pregLimit = $pregLimit;
        $this->groupAware = new LightweightGroupAware($definition);
        $this->counting = $counting;
        $this->awake = new GroupAwake($definition, $subject);
    }

    public function withGroup(GroupKey $group): string
    {
        $delegate = new PregDelegate($this->groupAware, $this->awake, $group);
        $replaced = $this->replacedByDelegate($delegate);
        $this->applyDelegate($delegate, $group);
        return $replaced;
    }

    private function replacedByDelegate(PregDelegate $delegate): string
    {
        return preg::replace_callback($this->definition->pattern, [$delegate, 'apply'], $this->subject, $this->pregLimit);
    }

    private function applyDelegate(PregDelegate $delegate, GroupKey $group): void
    {
        if ($delegate->replaced() === 0) {
            $this->applyUnmatchedSubject($group);
        } else {
            $this->counting->applyReplaced($delegate->replaced());
        }
    }

    private function applyUnmatchedSubject(GroupKey $group): void
    {
        if ($this->groupAware->hasGroup($group)) {
            $this->counting->applyReplaced(0);
        } else {
            throw new NonexistentGroupException($group);
        }
    }
}
