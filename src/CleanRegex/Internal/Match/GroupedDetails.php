<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupHandle;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Pcre\Signatures\ArraySignatures;
use TRegx\CleanRegex\Internal\Subject;

class GroupedDetails
{
    /** @var Base */
    private $base;
    /** @var DetailObjectFactory */
    private $factory;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->base = new ApiBase($definition, $subject);
        $this->factory = new DetailObjectFactory($subject);
    }

    public function groupedBy(GroupKey $group): array
    {
        $matches = $this->base->matchAllOffsets();
        if ($matches->hasGroup($group)) {
            return $this->groupedByGroup($matches, $group);
        }
        throw new NonexistentGroupException($group);
    }

    private function groupedByGroup(RawMatchesOffset $matches, GroupKey $group): array
    {
        $groupedBy = [];
        $handle = new GroupHandle(new ArraySignatures($matches->getGroupKeys()));
        $handled = $handle->groupHandle($group);
        foreach ($matches->getIndexes() as $index) {
            $text = $this->groupText($matches, $handled, $index, $group);
            $groupedBy[$text][] = $this->factory->mapToDetailObject($matches, $index);
        }
        return $groupedBy;
    }

    private function groupText(RawMatchesOffset $matches, int $handled, int $index, GroupKey $group): string
    {
        if (!$matches->isGroupMatched($handled, $index)) {
            throw (new GroupNotMatchedException("Expected to group matches by group $group, but the group was not matched"));
        }
        [$text] = $matches->getGroupTextAndOffset($handled, $index);
        return $text;
    }
}
