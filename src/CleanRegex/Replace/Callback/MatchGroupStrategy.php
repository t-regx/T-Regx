<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Match\Details\Group\ReplaceGroup;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;

class MatchGroupStrategy implements ReplaceCallbackArgumentStrategy
{
    /** @var GroupKey */
    private $groupId;

    public function __construct(GroupKey $groupId)
    {
        $this->groupId = $groupId;
    }

    public function mapArgument(ReplaceDetail $detail): ReplaceGroup
    {
        return $detail->group($this->groupId->nameOrIndex());
    }
}
