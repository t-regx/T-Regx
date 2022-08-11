<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Match\Group;

class MatchGroupStrategy implements ReplaceCallbackArgumentStrategy
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function mapArgument(Detail $detail): Group
    {
        return $detail->group($this->group->nameOrIndex());
    }
}
