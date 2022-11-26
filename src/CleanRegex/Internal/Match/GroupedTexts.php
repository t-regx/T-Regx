<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupHandle;
use TRegx\CleanRegex\Internal\Pcre\Signatures\ArraySignatures;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class GroupedTexts
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function groupedBy(GroupKey $group): array
    {
        preg::match_all($this->definition->pattern, $this->subject->asString(), $matches, \PREG_OFFSET_CAPTURE);
        if (\array_key_exists($group->nameOrIndex(), $matches)) {
            return $this->groupedByGroup($matches, $group);
        }
        throw new NonexistentGroupException($group);
    }

    private function groupedByGroup(array $matches, GroupKey $group): array
    {
        $groupedBy = [];
        $handle = new GroupHandle(new ArraySignatures(\array_keys($matches)));
        foreach ($matches[$handle->groupHandle($group)] as $index => $match) {
            $groupedBy[$this->groupText($match, $group)][] = $matches[0][$index][0];
        }
        return $groupedBy;
    }

    private function groupText($match, GroupKey $group): string
    {
        if ($match === '') {
            throw $this->groupNotMatched($group);
        }
        [$value, $offset] = $match;
        if ($offset === -1) {
            throw $this->groupNotMatched($group);
        }
        return $value;
    }

    private function groupNotMatched(GroupKey $group): GroupNotMatchedException
    {
        return new GroupNotMatchedException("Expected to group matches by group $group, but the group was not matched");
    }
}
