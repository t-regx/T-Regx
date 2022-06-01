<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;

class GroupHandle
{
    /** @var Signatures */
    private $signatures;

    public function __construct(Signatures $signatures)
    {
        $this->signatures = $signatures;
    }

    public function groupHandle(GroupKey $groupKey): int
    {
        return $this->signatures->signature($groupKey)->index();
    }
}
