<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupReplace;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class PregDelegate
{
    /** @var GroupAware */
    private $aware;
    /** @var GroupAwake */
    private $awake;
    /** @var GroupKey */
    private $group;
    /** @var int */
    private $index;

    public function __construct(GroupAware $aware, GroupAwake $awake, GroupKey $group)
    {
        $this->aware = $aware;
        $this->awake = $awake;
        $this->group = $group;
        $this->index = 0;
    }

    public function apply(array $match): string
    {
        $groupText = $this->matchedGroupText($match);
        $this->index++;
        return $groupText;
    }

    private function matchedGroupText(array $match): string
    {
        $nameOrIndex = $this->group->nameOrIndex();
        if (\array_key_exists($nameOrIndex, $match)) {
            return $this->groupText($match, $nameOrIndex);
        }
        throw $this->absentGroupException();
    }

    private function groupText(array $match, $nameOrIndex): string
    {
        if ($match[$nameOrIndex] === '') {
            return $this->matchedEmptyGroup();
        }
        return $match[$nameOrIndex];
    }

    private function matchedEmptyGroup(): string
    {
        if ($this->awake->groupMatched($this->index, $this->group)) {
            return '';
        }
        throw GroupNotMatchedException::forReplacement($this->group);
    }

    private function absentGroupException(): \Throwable
    {
        if ($this->aware->hasGroup($this->group)) {
            return GroupNotMatchedException::forReplacement($this->group);
        }
        return new NonexistentGroupException($this->group);
    }

    public function replaced(): int
    {
        return $this->index;
    }
}
