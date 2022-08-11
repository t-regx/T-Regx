<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Match\Group;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

class MatchGroupStrategy implements ReplaceCallbackArgumentStrategy
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function mapArgument(ReplaceDetail $detail): Group
    {
        return $detail->group($this->group->nameOrIndex());
    }
}
